<?php

namespace phpOMS\Utils\Git;

class Repository
{
    private $path       = '';
    private $bare       = false;
    private $envOptions = [];

    public function __construct(string $path)
    {
        $this->setPath($path);
    }

    public function create(string $source = null, bool $bare = false)
    {
        if (!is_dir($this->path) || file_exists($this->path . '/.git')) {
            throw new \Exception('Already repository');
        }

        if (isset($source)) {
            $this->clone($source);
        } else {
            $this->init($bare);
        }
    }

    private function setPath(string $path)
    {
        if (!is_dir($this->path)) {
            throw new \Exception('Is not a directory');
        }

        $this->path = realpath($path);

        if (file_exists($this->path . '/.git') && is_dir($this->path . '/.git')) {
            $this->bare = false;
            // Is this a bare repo?
        } elseif (is_file($this->path . '/config')) {
            $parse_ini = parse_ini_file($this->path . '/config');

            if ($parse_ini['bare']) {
                $this->bare = true;
            }
        }
    }

    private function run($cmd)
    {
        $cmd   = Git::getBin() . ' ' . $cmd;
        $pipes = [];
        $desc  = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        if (count($_ENV) === 0) {
            $env = null;
            foreach ($this->envOptions as $key => $value) {
                putenv(sprintf("%s=%s", $key, $value));
            }
        } else {
            $env = array_merge($_ENV, $this->envOptions);
        }

        $resource = proc_open($cmd, $desc, $pipes, $this->path, $env);
        $stdout   = stream_get_contents($pipes[1]);
        $stderr   = stream_get_contents($pipes[2]);

        foreach ($pipes as $pipe) {
            fclose($pipe);
        }

        $status = trim(proc_close($resource));

        if ($status) {
            throw new Exception($stderr);
        }

        return $stdout;
    }

    public function getDirectoryPath() : string
    {
        return $this->bare ? $this->path : $this->path . '/.git';
    }

    public function status() : string
    {
        return $this->run('status');
    }

    public function add($files = '*')
    {
        if (is_array($files)) {
            $files = '"' . implode('" "', $files) . '"';
        } elseif (!is_string($files)) {
            throw new \Exception('Wrong type');
        }

        return $this->run('add ' . $files . ' -v');
    }

    public function rm($files = '*', bool $cached = false) : string
    {
        if (is_array($files)) {
            $files = '"' . implode('" "', $files) . '"';
        } elseif (!is_string($files)) {
            throw new \Exception('Wrong type');
        }

        return $this->run('rm ' . ($cached ? '--cached ' : '') . $files);
    }

    public function commit($msg = '', $all = true) : string
    {
        return $this->run('commit ' . ($all ? '-av' : '-v') . ' -m' . escapeshellarg($msg));
    }

    public function cloneTo(string $target) : string
    {
        if (!is_dir($target)) {
            throw new \Exception('Not a directory');
        }

        return $this->run('clone --local ' . $this->path . ' ' . $target);
    }

    public function cloneFrom(string $source) : string
    {
        if (!is_dir($source)) {
            throw new \Exception('Not a directory');
        }

        // todo: is valid git repository?

        return $this->run('clone --local ' . $source . ' ' . $this->path);
    }

    public function cloneRemote(string $source) : string
    {
        // todo: is valid remote git repository?

        return $this->run('clone ' . $source . ' ' . $this->path);
    }

    public function clean(bool $dirs = false, bool $force = false) : string
    {
        return $this->run('clean ' . ($force ? ' -f' : '') . ($dirs ? ' -d' : ''));
    }

    public function createBranch(string $branch, bool $force = false) : string
    {
        return $this->run('branch ' . ($force ? '-D' : '-d') . ' ' . $branch);
    }

    public function getBranches() : array
    {
        $branches = explode('\n', $this->run('branch'));

        foreach ($branches as $key => &$branch) {
            $branch = trim($branch);

            if ($branch === '') {
                unset($branches[$key]);
            }
        }

        return $branches;
    }

    public function getBranchesRemote() : array
    {
        $branches = explode("\n", $this->run('branch -r'));

        foreach ($branches as $key => &$branch) {
            $branch = trim($branch);

            if ($branch === '' || strpos($branch, 'HEAD -> ') !== false) {
                unset($branches[$key]);
            }
        }

        return $branches;
    }

    public function getActiveBranch() : string
    {
        $branches = $this->getBranches();
        $active   = preg_grep('/^\*/', $branches);
        reset($active);

        return current($active);
    }

    public function checkout($branch) : string
    {
        return $this->run('checkout ' . $branch);
    }

    public function merge($branch) : string
    {
        return $this->run('merge ' . $branch . ' --no-ff');
    }

    public function fetch() : string
    {
        return $this->run('fetch');
    }

    public function addTag(string $tag, string $message = null) : string
    {
        return $this->run('tag -a ' . $tag . ' -m ' . escapeshellarg($message ?? $tag));
    }

    public function getTags(string $pattern) : string
    {
        $tags = explode('\n', $this->run('tag -l ' . $pattern));

        foreach ($tags as $key => &$tag) {
            $tag = trim($tag);

            if ($tag === '') {
                unset($tags[$key]);
            }
        }

        return $tags;
    }

    public function push(string $remote, string $branch) : string
    {
        return $this->run('push --tags ' . $remote . ' ' . $branch);
    }

    public function pull(string $remote, string $branch) : string
    {
        return $this->run('pull ' . $remote . ' ' . $branch);
    }

    public function log(string $format = null) : string
    {
        return !isset($format) ? $this->run('log') : $this->run('log --pretty=format:"' . $format . '"');
    }

    public function setDescription(string $description)
    {
        file_put_contents($this->getDirectoryPath(), $description);
    }

    public function getDescription() : string
    {
        return file_get_contents($this->getDirectoryPath() . '/description');
    }

    public function setEnv(string $key, string $value)
    {
        $this->envOptions[$key] = $value;
    }

    public function getMessage(string $commit) : string
    {
        return $this->run('log --format=%B -n 1 ' . $commit);
    }

    public function getCommitsCount() : string
    {
        return $this->run('shortlog -s -n --all');
    }

    public function getCommitsBy(string $author, \DateTime $start, \DateTime $end) : string
    {
        return $this->run('git log --before="' . $end->format('Y-m-d') . '" --after="' . $start->format('Y-m-d') . '" --author="' . $author . '" --reverse --pretty=format:"%cd  %h  %s" --date=short');
	}

    public function getRemote() : string
    {
        return $this->run('config --get remote.origin.url');
    }

    public function getNewest() : string
    {
        return $this->run('log --name-status HEAD^..HEAD');
	}
}
