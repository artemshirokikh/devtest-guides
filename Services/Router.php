<?php

namespace Services;

class Router extends Service
{
    protected array $uri;
    protected string $method;
    protected array $input;

    // todo Do I need this? Maybe $this->uri is enough?
    protected ?string $object;
    protected ?string $objectId;
    protected ?string $subObject;
    protected ?string $subObjectId;


    public function __construct()
    {
        $this->loadData();
        $this->parseData();
    }


    protected function loadData(): void
    {
        // Get and parse URL path: /name1/name2/...
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );
        $this->uri = $uri;

        $this->method = $_SERVER['REQUEST_METHOD'];

        // Get and parse request body
        $this->input = (array) json_decode(file_get_contents('php://input'), true);
    }

    protected function parseData(): void
    {
        // todo Add version?
        // todo Do I need this? Maybe $this->uri is enough?

        if (isset($this->uri[1])) {
            $this->object = $this->uri[1];
            if (isset($this->uri[2])) {
                $this->objectId = $this->uri[2];
            }
        }

        if (isset($this->uri[3])) {
            $this->subObject = $this->uri[3];
            if (isset($this->uri[4])) {
                $this->subObjectId = $this->uri[4];
            }
        }
    }

    public function setCommonHeaders(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

    public function respondNotFound(bool $exit = false): void
    {
        $this->setCommonHeaders();
        header('HTTP/1.1 404 Not Found'); // todo Use constant or enum
        if ($exit) {
            exit;
        }
    }

    public function respond(array $response, bool $jsonEncode = true): void
    {
        $this->setCommonHeaders();
        header($response['status']);
        if (isset($response['body'])) {
            $body = $jsonEncode
                ? json_encode($response['body'])
                : $response['body'];
            echo $body;
        }
    }

    /**
     * Get name from URI, like 'most_crucial', and transform it to 'MostCrucial'
     *
     * @param string|null $name
     * @return string|null
     */
    public function transformName(?string $name): ?string
    {
        if ($name === null) {
            return null;
        }

        $trans = '';
        $splitted = explode('_', $name);
        foreach ($splitted as $item) {
            $trans .= ucfirst($item);
        }
        return $trans;
    }

    public function transformId(?string $id): ?string
    {
        if ($id === null) {
            return null;
        }
        return strtoupper($id);
    }


    public function getObject(): ?string
    {
        return $this->object;
    }

    public function getObjectId(): ?string
    {
        return $this->objectId;
    }

    public function getSubObject(): ?string
    {
        return $this->subObject;
    }

    public function getSubObjectId(): ?string
    {
        return $this->subObjectId;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getInput(): array
    {
        return $this->input;
    }
}
