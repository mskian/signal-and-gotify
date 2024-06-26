# Signal and Gotify Proxy Form

Send Messages to Gotify Server and Signal recipients from HTML Form by bypassing the CORS errors.

- install Required Packages

```sh
composer require symfony/yaml symfony/filesystem
```

- Create `yml` File in **Home Directory** to Store the API URL of Gotify and Signal

> `pushnotify.yml`

```yml
api:
  signal_url: "https://signal.example.com"
  gotify_url: "https://push.example.com"
```

## Reference

- Signal API - <https://github.com/bbernhard/signal-cli-rest-api>
- Gotify - <https://gotify.net/>

## LICENSE

MIT
