<p align="center">
    <img src="/art/logo.png" width="50%" alt="b2 spirit logo">
</p>

<p align="center">
    <a href="https://packagist.org/packages/noxterr/spirit"><img src="https://img.shields.io/packagist/dt/noxterr/spirit" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/noxterr/spirit"><img src="https://img.shields.io/packagist/v/noxterr/spirit" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/noxterr/spirit"><img src="https://img.shields.io/packagist/l/noxterr/spirit" alt="License"></a>
</p>

B2 Spirit is a Laravel package designed to handle file transfers with Backblaze B2 Cloud Storage.

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
B2_KEY=`YOUR_B2_KEY` # You can put your master key for now. Creating a key with the privileges you need also works
B2_BUCKET_NAME=`YOUR_B2_BUCKET_NAME` # The globally-available name you gave your bucket
B2_BUCKET_ID=`YOUR_B2_BUCKET_ID`
B2_ACCOUNT_ID=`YOUR_B2_ACCOUNT_ID`
```

## Usage

### Uploading a File

```php
use Noxterr\Spirit\Spirit;

$spirit = new Spirit();

$uploaded_file = $spirit->uploadFile($file);
```

### Downloading a File

```php
use Noxterr\Spirit\Spirit;

$spirit = new Spirit();

$file = $spirit->downloadFile($file_name);
```

### Handling respones

Every time an action is carried out on Spirit, a response is provided to the user under the form of a so-called ClassReturn.

This is an object that returns standardized data, making it easier to interface with your custom application.

The object is as follows

```php

// In another class
$response = new \ClassReturn();

return $response;

/** This returns
 * {
 *    errcode: 1 | 0,
 *    message: string | null (contains the error information if needed)
 *    data: mixed | null -> contains the data from a response
 * }
*/
```

With this flow, you can always ensure to check errors like so `if ($response->errcode != 0) { Error handling }`, so is easy to use.


### Using more keys

If you have multiple keys, at this moment, you can decice to split the flow between read-only and write-only keys.

Add to your `.env` the keys
```env
B2_READ_KEY_ID=`YOUR_B2_READ_ONLY_KEY` # This key has read-only (download, list files, etc) permissions
B2_WRITE_KEY_ID=`YOUR_B2_WRITE_ONLY_KEY` # This key has write-only (upload, delete files, etc) permissions
```

In your code, use it like so

```php
use Noxterr\Spirit\Spirit;

$spirit = new Spirit([
    'has_multiple_keys' => true,
]);

$uploaded_file = $spirit->uploadFile($file);

$downloaded_file = $spirit->downloadFile($uploaded_file->name);
```

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Support

For any issues, please open an issue on GitHub.
