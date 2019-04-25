<?php
namespace Grav\Plugin\Login\OAuth2\Providers;

use Grav\Common\Data\Data;
use Grav\Common\Grav;

class JiraProvider extends BaseProvider
{
    protected $name = 'Jira';
    protected $classname = 'Mrjoops\\OAuth2\\Client\Provider\\Jira';

    public function __construct()
    {
        parent::__construct();
        $admin = Grav::instance()['oauth2']->isAdmin();
        $this->config = new Data(Grav::instance()['config']->get('plugins.login-oauth2-extras' . ($admin ? '.admin' : '')));
    }

    public function initProvider(array $options)
    {
        $options += [
            'clientId'      => $this->config->get('providers.jira.client_id'),
            'clientSecret'  => $this->config->get('providers.jira.client_secret'),
            'redirectUri'   => $this->getCallbackUri(),
        ];

        parent::initProvider($options);
    }

    public function getAuthorizationUrl()
    {
        $options = ['state' => $this->state];
        $options['scope'] = $this->config->get('providers.jira.options.scope');

        return $this->provider->getAuthorizationUrl($options);
    }

    public function getUserData($user)
    {
        $data_user = [
            'id'       => $user->getId(),
            'login'    => $user->getName(),
            'fullname' => $user->getName(),
            'email'    => $user->getEmail(),
            'jira'     => [
                'company'    => $user->getName(),
                'avatar_url' => $user->getAvatarUrl(),
            ],
        ];

        return $data_user;
    }
}