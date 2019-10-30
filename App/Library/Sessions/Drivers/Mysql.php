<?php

declare(strict_types=1);

namespace App\Library\Sessions\Drivers;

use App\Models\Session;

/**
 * Class Mysql
 * @package App\Library\Sessions\Drivers
 */
final class Mysql extends SessionDriver
{

    /**
     * @var Session;
     */
    private $sessionModel;

    /**
     * @var array
     */
    private $sessionData = [];

    private function fetchSessionModel(): void
    {
        $this->sessionModel = Session::find($this->getSessionIdentifier());

        if ($this->sessionModel === null) {
            $this->sessionModel = new Session();
            $this->sessionModel->key = $this->sessionKey;
            $this->sessionModel->ip = $this->ip ?? 'No Ip';
            $this->sessionModel->user_agent = $this->userAgent ?? 'Unknown';
            $this->sessionModel->session = [];
            $this->sessionModel->save();
        }

        $this->sessionData = $this->sessionModel->session;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->ensureModel();
        $this->sessionData[$key] = $value;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $this->ensureModel();

        return $this->sessionData[$key] ?? $default;
    }

    /**
     * Persist session data
     */
    public function save(): void
    {
        $this->ensureModel();
        $this->sessionModel->session = $this->sessionData;
        $this->sessionModel->save();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->sessionData;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->sessionData, JSON_THROW_ON_ERROR, 512);
    }

    /**
     * Ends and deletes session
     * @throws \Exception
     */
    public function delete(): void
    {
        $this->sessionModel->delete();
    }

    /**
     * Set to expire session
     * @param bool $expire
     */
    public function setSessionExpiration(bool $expire): void
    {
        $this->ensureModel();
        $this->sessionModel->remember = $expire;
    }

    private function ensureModel(): void
    {
        if (!$this->sessionModel instanceof Session) {
            $this->fetchSessionModel();
        }
    }
}
