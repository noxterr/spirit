# b2-spirit

b2-spirit is a Laravel package designed to handle file transfers with Backblaze B2 Cloud Storage.

## Features

- Seamless integration with Laravel
- Easy file uploads and downloads
- Secure file storage with Backblaze B2

## Installation

To install the package, use Composer:

```bash
composer require noxterr/spirit
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Noxterr\Spirit\SpiritServiceProvider"
```

Set your Backblaze B2 credentials in the `.env` file:

```env
B2_MASTER_KEY=`YOUR_B2_MASTER_KEY` # This is used only once
B2_KEY=`YOUR_B2_KEY` # A master key isn't reccomended. Create a key with the privileges you need
B2_BUCKET_NAME='YOUR_B2_BUCKET_NAME' # The globally-available name you gave your bucket
B2_BUCKET_ID=`YOUR_B2_BUCKET_ID`
```

Then, run the command to get your

At last, change your `.env` again:

```env
B2_MASTER_KEY=`` # Either put nothing or remove this altogether. It was needed only to get the URL
B2_API_URL=`YOUR_B2_API_URL` # You get this after calling the command
```

## Usage

### Uploading a File

```php
use Noxterr\Spirit\Facades\Spirit;

Spirit::upload($filePath, $content);
```

### Downloading a File

```php
use Noxterr\Spirit\Facades\Spirit;

$file = Spirit::download($filePath);
```

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Support

For any issues, please open an issue on GitHub.
