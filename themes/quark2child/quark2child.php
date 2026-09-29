<?php

namespace Grav\Theme;

class Quark2Child extends Quark2
{
    public static function getSubscribedEvents()
    {
        return ['onPageInitialized' => ['onPageInitialized', 0]];
    }

    public function onPageInitialized()
    {
        if (!empty($_SERVER['HTTP_HX_REQUEST'])) {
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

            if (preg_match('/\/components\/([a-zA-Z0-9_-]+)/', $uri, $matches)) {
                $component = $matches[1];
                $filePath = "/var/www/grav/user/themes/quark2child/templates/components/{$component}.html.twig";

                if (file_exists($filePath)) {
                    $content = file_get_contents($filePath);

                    // LOGIQUE DE DÉCISION
                    // On affiche le début (résultat) SI :
                    // 1. C'est une méthode de modification (PUT, POST, etc.)
                    // 2. OU c'est trigger_delay avec un paramètre de recherche 'q'
                    if (
                        ($method === 'PUT' || $method === 'POST' || $method === 'PATCH' || $method === 'DELETE') || 
                        ($component === 'trigger_delay' && isset($_GET['q']) && !empty($_GET['q']))
                    ) {
                        // GARDER LE DÉBUT (Résultat)
                        $content = preg_replace('/\{% if .*? %\}(.*?)\{% else %\}.*?\{% endif %\}/s', '$1', $content);
                    } else {
                        // GARDER LA FIN (Boutons / Input)
                        $content = preg_replace('/\{% if .*? %\}.*?\{% else %\}(.*?)\{% endif %\}/s', '$1', $content);
                    }

                    // Nettoyage des balises Twig
                    $content = preg_replace('/\{%.*?%\}/', '', $content);
                    $content = preg_replace('/\{#.*?#\}/s', '', $content);

                    // Remplacement des variables
                    if (isset($_GET['q'])) {
                        $content = str_replace('{{ q }}', htmlspecialchars($_GET['q']), $content);
                    }

                    echo $content;
                    exit;
                } else {
                    echo "Fichier non trouvé : " . $filePath;
                    exit;
                }
            }
        }
    }
}

