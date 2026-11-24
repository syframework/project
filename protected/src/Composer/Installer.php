<?php
namespace Project\Composer;

use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\Installer\PackageEvent;
use Composer\Script\Event;

/**
 * Installs the sy/bootstrap-* plugins after Composer has generated the autoloader.
 *
 * `post-package-install` runs before `vendor/autoload.php` is dumped, so a
 * callback on Sy\Bootstrap\Plugin cannot run during `composer create-project`.
 * This bridge runs from `post-install-cmd` / `post-update-cmd`, scans the
 * installed plugins and reuses the canonical installer.
 *
 * A state file remembers the installed versions: templates are copied only on
 * a real first install, never on a later update. When the state file is
 * missing (project upgrading to this installer), plugins are treated as
 * updates so existing project templates are not overwritten.
 */
class Installer {

	/**
	 * @param Event $event
	 */
	public static function postPackages(Event $event) {
		$composer  = $event->getComposer();
		$vendor    = $composer->getConfig()->get('vendor-dir');
		$stateFile = $vendor . '/sy/plugins.json';

		$state    = self::readState($stateFile);
		$firstRun = !is_file($stateFile);

		$localRepo = $composer->getRepositoryManager()->getLocalRepository();
		$installed = [];
		foreach ($localRepo->getPackages() as $package) {
			$name = $package->getName();
			if ($name === 'sy/bootstrap' or !str_starts_with($name, 'sy/bootstrap-')) continue;
			$installed[$name] = $package;
		}

		$next = [];
		foreach ($installed as $name => $package) {
			$version     = $package->getPrettyVersion();
			$next[$name] = $version;

			$isNew = !array_key_exists($name, $state);
			if ($isNew and !$firstRun) {
				$operation = new InstallOperation($package);
				$eventName = 'post-package-install';
			} elseif (($isNew and $firstRun) or $state[$name] !== $version) {
				$operation = new UpdateOperation($package, $package);
				$eventName = 'post-package-update';
			} else {
				continue;
			}

			$packageEvent = new PackageEvent(
				$eventName,
				$composer,
				$event->getIO(),
				$event->isDevMode(),
				$localRepo,
				[$operation],
				$operation
			);

			if ($eventName === 'post-package-install') {
				\Sy\Bootstrap\Plugin::postPackageInstall($packageEvent);
			} else {
				\Sy\Bootstrap\Plugin::postPackageUpdate($packageEvent);
			}
		}

		self::writeState($stateFile, $next);
	}

	/**
	 * @param string $file
	 * @return array
	 */
	private static function readState($file) {
		if (!is_file($file)) return [];

		$state = json_decode((string) file_get_contents($file), true);
		return is_array($state) ? $state : [];
	}

	/**
	 * @param string $file
	 * @param array  $state
	 */
	private static function writeState($file, array $state) {
		$dir = dirname($file);
		if (!is_dir($dir)) mkdir($dir, 0777, true);
		file_put_contents($file, json_encode($state, JSON_PRETTY_PRINT));
	}

}
