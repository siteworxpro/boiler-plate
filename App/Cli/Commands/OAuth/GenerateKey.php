<?php

declare(strict_types=1);

namespace App\Cli\Commands\OAuth;

use App\Cli\Commands\Command;
use App\Library\App;

/**
 * Class GenerateKey
 * @package App\Cli\Commands\OAuth
 */
class GenerateKey extends Command
{

    /**
     * @return string
     */
    public static function getHelp(): string
    {
        return 'Generates new oAuth Key Set use --write flag to save to file <red>WARNING! Destructive</red>';
    }

    /**
     * @return string
     */
    public static function commandSignature(): string
    {
        return 'generate-key';
    }

    /**
     * @return int Return exit code
     * @throws \Exception
     */
    public function execute(): int
    {
        $this->cli->arguments->add([
            'writeFile' => [
                'longPrefix' => 'write',
                'castTo' => 'bool',
                'default' => false,
                'noValue' => true
            ]
        ]);

        $this->cli->arguments->parse();

        $config = [
            'digest_alg' => 'sha512',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        $res = openssl_pkey_new($config);

        openssl_pkey_export($res, $privKey);

        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey['key'];

        $encryptionKey = base64_encode(random_bytes(32));

        if ($this->cli->arguments->get('writeFile')) {
            $file = fopen(App::di()->config->get('run_dir') . '/authorization.key', 'wb');

            fwrite($file, $pubKey);
            fwrite($file, $privKey);

            fclose($file);

            $configFile = App::di()->config->get('run_dir') . '/var/config/config.php';
            file_put_contents(
                $configFile,
                str_replace('__encryption_key__', $encryptionKey, file_get_contents($configFile))
            );

            $this->cli->info('Your key has been written. Keep this key set in a safe place');
            $this->cli->info('Your config has also been updated with your new encryption key');
        } else {
            $this->cli->yellow($privKey);
            $this->cli->green($pubKey);
            $this->cli->blue($encryptionKey);
        }

        return 0;
    }
}
