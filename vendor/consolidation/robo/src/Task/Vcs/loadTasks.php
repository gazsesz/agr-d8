<?php
namespace Robo\Task\Vcs;

trait loadTasks
{
    /**
     * @param string $username
     * @param string $password
     * @param string $pathToSvn
     *
     * @return \Robo\Task\Vcs\SvnStack
     */
    protected function taskSvnStack($username = '', $password = '', $pathToSvn = 'svn')
    {
        return $this->task(SvnStack::class, $username, $password, $pathToSvn);
    }

    /**
     * @param string $pathToGit
     *
     * @return \Robo\Task\Vcs\GitStack
     */
    protected function taskGitStack($pathToGit = 'git')
    {
        return $this->task(GitStack::class, $pathToGit);
    }

    /**
     * @param string $pathToHg
     *
     * @return \Robo\Task\Vcs\HgStack
     */
    protected function taskHgStack($pathToHg = 'hg')
    {
        return $this->task(HgStack::class, $pathToHg);
    }
}
