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

    private function fetchSessionModel(): void
    {
        $this->sessionModel = Session::find($this->getSessionIdentifier());
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->ensureModel();
        $this->sessionModel->session[$key] = $value;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $this->ensureModel();

        return $this->sessionModel->session[$key] ?? $default;
    }

    /**
     * Persist session data
     */
    public function save(): void
    {
        $this->ensureModel();
        $this->sessionModel->save();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->sessionModel->session;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->sessionModel->session, JSON_THROW_ON_ERROR, 512);
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
