<?php

namespace phpOMS\Utils\Git;

class Git {
	protected static $bin = '/usr/bin/git';

	public static function setBin(string $path) {
		self::$bin = $path;
	}

	public static function getBin() : string {
		return self::$bin;
	}

	public static function test() : bool {
		$pipes = [];
		$resource = proc_open(Git::getBin(), [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);

		$stdout = stream_get_contents($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);

		foreach($pipes as $pipe) {
			fclose($pipe);
		}

		return trim(proc_close($resource)) !== 127;
	}
}
