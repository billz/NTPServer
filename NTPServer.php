<?php

/**
 * NTP Server Plugin
 *
 * @description A Network Time Protocol (NTP) server for RaspAP 
 * @author      Bill Zimmerman <billzimmerman@gmail.com>
 * @license     https://github.com/RaspAP/raspap-insiders/blob/master/LICENSE
 * @see         src/RaspAP/Plugins/PluginInterface.php
 * @see         src/RaspAP/UI/Sidebar.php
 */

namespace RaspAP\Plugins\NTPServer;

use RaspAP\Plugins\PluginInterface;
use RaspAP\UI\Sidebar;

class NTPServer implements PluginInterface
{

    private string $pluginPath;
    private string $pluginName;
    private string $templateMain;
    private string $serviceStatus;
    private string $ntpConfig;

    public function __construct(string $pluginPath, string $pluginName)
    {
        $this->pluginPath = $pluginPath;
        $this->pluginName = $pluginName;
        $this->templateMain = 'main';
        $this->ntpConfig = '/etc/ntpsec/ntp.conf';
     }

    /**
     * Initializes NTP plugin and creates a custom sidebar item
     *
     * @param Sidebar $sidebar an instance of the Sidebar
     * @see src/RaspAP/UI/Sidebar.php
     * @see https://fontawesome.com/icons
     */
    public function initialize(Sidebar $sidebar): void
    {

        $label = _('NTP Server');
        $icon = 'far fa-clock';
        $action = 'plugin__'.$this->getName();
        $priority = 72;
        $sidebar->addItem($label, $icon, $action, $priority);
    }

    /**
     * Handles a page action by processing inputs and rendering a plugin template
     *
     * @param string $page the current page route
     */
    public function handlePageAction(string $page): bool
    {
        // Verify that this plugin should handle the page
        if (strpos($page, "/plugin__" . $this->getName()) === 0) {

            // Instantiate a StatusMessage object
            $status = new \RaspAP\Messages\StatusMessage;

            if (!RASPI_MONITOR_ENABLED) {
                if (isset($_POST['SaveNTPsettings'])) {
                    if(isset($_POST['ntpconfigedit']) && $_POST['ntpconfigedit'] == '1') {
                        $config = trim($_POST['txtntpconfigraw']);
                        if (empty($config)) {
                            $status->addMessage('NTP configuration cannot be empty', 'danger');
                        } else {
                            $return = $this->saveNTPConfigRaw($status, $config);
                            if ($return == 0) {
                                $status->addMessage('Restarting ntpd.service', 'info');
                                exec('sudo /bin/systemctl restart ntpd.service', $return);
                            }
                        }
                    } elseif (isset($_POST['server'])) {
                        $servers = $_POST['server'];
                        if (count($servers) == 0) {
                            $status->addMessage('Please enter a valid NTP server', 'danger');
                        } else {
                            $return = $this->saveNTPSettings($status, $servers);
                            if ($return == 0) {
                              $status->addMessage('Restarting ntpd.service', 'info');
                              exec('sudo /bin/systemctl restart ntpd.service', $return);
                            }
                        }
                    }
                } elseif (isset($_POST['StartNTPservice'])) {
                    $status->addMessage('Attempting to start ntpd.service', 'info');
                    exec('sudo /bin/systemctl start ntpd.service', $return);
                    foreach ($return as $line) {
                        $status->addMessage($line, 'info');
                    }
                } elseif (isset($_POST['StopNTPservice'])) {
                    $status->addMessage('Attempting to stop ntpd.service', 'info');
                    exec('sudo /bin/systemctl stop ntpd.service', $return);
                    foreach ($return as $line) {
                        $status->addMessage($line, 'info');
                    }
                }
            }
            exec('cat '.$this->ntpConfig, $ntpConfig);
            foreach ($ntpConfig as $line) {
                if (strpos($line, 'server') === 0) {
                    $server = trim(substr($line, strlen('server')));
                    $ntpServers[] = $server;
                }
            };
            exec("ntpd -V", $version);
            $ntpDaemon = $version[0];
            $ntpTime = shell_exec("ntptime");
            $ntpConfig = implode("\n", $ntpConfig);

            exec("pidof ntpd | wc -l", $ntpstatus);
            $serviceStatus = $ntpstatus[0] == 0 ? "down" : "up";

            exec("sudo /usr/bin/ntpq -p", $output);
            $serviceLog = implode("\n", $output);

            // Populate template data
            $__template_data = [
                'title' => _('NTP Server'),
                'description' => _('A Network Time Protocol (NTP) server for RaspAP'),
                'author' => _('Bill Z'),
                'uri' => 'https://github.com/RaspAP/raspap-insiders',
                'icon' => $this->icon,
                'serviceStatus' => $serviceStatus,
                'serviceName' => 'ntpd.service',
                'ntpServers' => $ntpServers,
                'ntpDaemon' => $ntpDaemon,
                'ntpTime' => $ntpTime,
                'ntpConfig' => $ntpConfig,
                'serviceLog' => $serviceLog,
                'action' => 'plugin__'.$this->getName(),
                'pluginName' => $this->getName()
            ];
            echo $this->renderTemplate($this->templateMain, compact(
                "status",
                "__template_data"
            ));
            return true;
        }
        return false;
    }

    /**
     * Renders a template from inside a plugin directory
     * @param string $templateName
     * @param array $__data
     */
    public function renderTemplate(string $templateName, array $__data = []): string
    {
        $templateFile = "{$this->pluginPath}/{$this->getName()}/templates/{$templateName}.php";

        if (!file_exists($templateFile)) {
            return "Template file {$templateFile} not found.";
        }
        if (!empty($__data)) {
            extract($__data);
        }
        ob_start();
        include $templateFile;
        return ob_get_clean();
    }

    /**
     * Persist user settings to NTP config
     *
     * @param object $status
     * @param array $servers
     * @param string $ntpconfig
     * @return boolean
     */
    protected function saveNTPSettings($status, $servers)
    {
        exec('cat '.$this->ntpConfig, $ntpconfig);
        if (empty($ntpconfig)) {
            $status->addMessage(sprintf(_('NTP configuration not found at %s'), $this->ntpConfig), 'danger');
            return false;
        }
        $ntpconfig = array_filter($ntpconfig, function ($line) {
            return strpos(trim($line), 'server ') !== 0;
        });
        $servers = array_map(function ($server) {
            return 'server ' . trim($server);
        }, $servers);
        $ntpconfig = array_merge($ntpconfig, $servers);
        $ntpconfig = implode("\n", $ntpconfig);
        file_put_contents("/tmp/ntpdata", $ntpconfig);
        system('sudo cp /tmp/ntpdata '.$this->ntpConfig, $result);
        if ($result == 0) {
            $status->addMessage('NTP configuration updated', 'success');
        }
        return $result;
    }

    /**
     * Persist raw NTP config
     *
     * @param object $status
     * @param array $ntpconfig
     * @return boolean
     */
    protected function saveNTPConfigRaw($status, $ntpconfig)
    {
        file_put_contents("/tmp/ntpdata", $ntpconfig);
        system('sudo cp /tmp/ntpdata '.$this->ntpConfig, $result);
        if ($result == 0) {
            $status->addMessage('NTP configuration updated', 'success');
        }
        return $result;
    }

    // Static method to load persisted data
    public static function loadData(): ?self
    {
        $filePath = "/tmp/plugin__".self::getName() .".data";
        if (file_exists($filePath)) {
            $data = file_get_contents($filePath);
            return unserialize($data);
        }
        return null;
    }

    // Returns an abbreviated class name
    public static function getName(): string
    {
        return basename(str_replace('\\', '/', static::class));
    }
}

