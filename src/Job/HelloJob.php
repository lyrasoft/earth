<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Job;

use Windwalker\Core\Mailer\Mailer;
use Windwalker\Core\Mailer\MailMessage;
use Windwalker\Queue\Job\JobInterface;

/**
 * The HelloJob class.
 *
 * @since  __DEPLOY_VERSION__
 */
class HelloJob implements JobInterface
{
    /**
     * Property message.
     *
     * @var  MailMessage
     * @since  __DEPLOY_VERSION__
     */
    protected $message;

    /**
     * HelloJob constructor.
     *
     * @param MailMessage $message
     */
    public function __construct(MailMessage $message)
    {
        $this->message = $message;
    }

    /**
     * getName
     *
     * @return  string
     */
    public function getName()
    {
        return 'hello';
    }

    public function execute()
    {
        throw new \Exception('YOO');

        Mailer::send($this->message);
    }
}
