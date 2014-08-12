#NessCMS

A fully collaborative and multilingual PHP CMS for the web.

## Installation

### Simple

On your web browser:

1. Go to the directory on wich you have installed the CMS
2. Follow the instructions

### Manual (e.g.: for development purposes)

1. Create a database and fill it in with the content of the "NessCMS.sql" file
2. Fill in the “config.sample.inc.php” file with informations on your database
3. Rename it “config.inc.php”
4. That's it! If you want to use the administration, log in with with the nickname and password "admin".

## Configuration

### URL Rewriting

If you want to use URL Rewriting on your website you must enable the option on the Admin of the CMS and adapt your server accordingly.

Here is an example using Nginx:
```ini
location /nesscms/ {
	if (!-d $request_filename) {
		rewrite ^/nesscms/([\w+=/-]+)$ /nesscms/index.php?location=$1 last; break;
	}

	location ~ /(cache|controllers|languages|models|views)/ {
		return 403;
	}
}
```
Note that access to the above folders should be forbidden in all cases.

## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

## Versioning

**NessCMS** respects the [Semantic Versioning 2.0.0](http://semver.org/spec/v2.0.0.html) creed.

## License

Licensed under the GPL v3 license.

## References

**NessCMS** profits from these great others projects: 
[Twitter Bootstrap](https://github.com/twbs/bootstrap) (MIT),
[jQuery](https://github.com/jquery/jquery) (MIT),
[jQuery Color](https://github.com/jquery/jquery-color) (MIT),
[Lazy Load Plugin for jQuery](https://github.com/tuupola/jquery_lazyload) (MIT),
[jBBCode](https://github.com/jbowens/jBBCode) (MIT),