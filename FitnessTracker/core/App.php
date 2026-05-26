<?php

/**
 * Router celé aplikace (přesně podle wiki).
 * Rozsekne URL na controller / metodu / parametry a zavolá to.
 */
class App {
    // Výchozí nastavení, pokud uživatel přijde bez parametru v URL
    protected $controller = 'WorkoutController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        // Získání a rozsekání URL
        $url = $this->parseUrl();

        // 1. KONTROLER: existuje pro první část URL příslušný soubor?
        if (isset($url[0]) && file_exists(__DIR__ . '/../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        // Načtení souboru s kontrolerem a vytvoření instance
        require_once __DIR__ . '/../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. METODA: existuje pro druhou část URL funkce uvnitř kontroleru?
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. PARAMETRY: vše, co v URL zbylo, jsou parametry (např. ID)
        $this->params = $url ? array_values($url) : [];

        // Spuštění vybrané metody ve vybraném kontroleru
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Bezpečné rozsekání URL adresy
    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
