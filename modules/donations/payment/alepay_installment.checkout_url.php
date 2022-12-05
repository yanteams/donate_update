<?php

/**
 * @Project WALLET 4.x
 * @Author TMS Holdings <contact@tms.vn>
 * @Copyright (C) 2018 TMS Holdings. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_MOD_WALLET'))
    die('Stop!!!');

$SECURE_SECRET = $payment_config['secure_secret'];
//$vpcURL = $payment_config['virtualPaymentClientURL'] . "?";
define('MATH_BIGINTEGER_MONTGOMERY', 0);

define('MATH_BIGINTEGER_BARRETT', 1);

define('MATH_BIGINTEGER_POWEROF2', 2);

define('MATH_BIGINTEGER_CLASSIC', 3);

define('MATH_BIGINTEGER_NONE', 4);

define('MATH_BIGINTEGER_VALUE', 0);

define('MATH_BIGINTEGER_SIGN', 1);

define('MATH_BIGINTEGER_VARIABLE', 0);

define('MATH_BIGINTEGER_DATA', 1);

define('MATH_BIGINTEGER_MODE_INTERNAL', 1);

define('MATH_BIGINTEGER_MODE_BCMATH', 2);

define('MATH_BIGINTEGER_MODE_GMP', 3);

define('MATH_BIGINTEGER_KARATSUBA_CUTOFF', 25);
define('CRYPT_HASH_MODE_INTERNAL', 1);
define('CRYPT_HASH_MODE_MHASH',    2);
define('CRYPT_HASH_MODE_HASH',     3);
define('CRYPT_RSA_ENCRYPTION_OAEP',  1);

define('CRYPT_RSA_ENCRYPTION_PKCS1', 2);

define('CRYPT_RSA_ENCRYPTION_NONE', 3);

define('CRYPT_RSA_SIGNATURE_PSS',  1);

define('CRYPT_RSA_SIGNATURE_PKCS1', 2);

define('CRYPT_RSA_ASN1_INTEGER',     2);

define('CRYPT_RSA_ASN1_BITSTRING',   3);

define('CRYPT_RSA_ASN1_OCTETSTRING', 4);

define('CRYPT_RSA_ASN1_OBJECT',      6);

define('CRYPT_RSA_ASN1_SEQUENCE',   48);

define('CRYPT_RSA_MODE_INTERNAL', 1);

define('CRYPT_RSA_MODE_OPENSSL', 2);

define('CRYPT_RSA_OPENSSL_CONFIG', dirname(__FILE__) . '/../openssl.cnf');

define('CRYPT_RSA_PRIVATE_FORMAT_PKCS1', 0);

define('CRYPT_RSA_PRIVATE_FORMAT_PUTTY', 1);

define('CRYPT_RSA_PRIVATE_FORMAT_XML', 2);

define('CRYPT_RSA_PRIVATE_FORMAT_PKCS8', 8);

define('CRYPT_RSA_PUBLIC_FORMAT_RAW', 3);

define('CRYPT_RSA_PUBLIC_FORMAT_PKCS1', 4);
define('CRYPT_RSA_PUBLIC_FORMAT_PKCS1_RAW', 4);

define('CRYPT_RSA_PUBLIC_FORMAT_XML', 5);

define('CRYPT_RSA_PUBLIC_FORMAT_OPENSSH', 6);

define('CRYPT_RSA_PUBLIC_FORMAT_PKCS8', 7);




if (!function_exists('crypt_random_string')) {
    /**
     * "Is Windows" test
     *
     * @access private
     */
    define('CRYPT_RANDOM_IS_WINDOWS', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');

    /**
     * Generate a random string.
     *
     * Although microoptimizations are generally discouraged as they impair readability this function is ripe with
     * microoptimizations because this function has the potential of being called a huge number of times.
     * eg. for RSA key generation.
     *
     * @param int $length
     * @return string
     * @access public
     */
    function crypt_random_string($length)
    {
        if (CRYPT_RANDOM_IS_WINDOWS) {
            // method 1. prior to PHP 5.3, mcrypt_create_iv() would call rand() on windows
            if (extension_loaded('mcrypt') && version_compare(PHP_VERSION, '5.3.0', '>=')) {
                return mcrypt_create_iv($length);
            }
            // method 2. openssl_random_pseudo_bytes was introduced in PHP 5.3.0 but prior to PHP 5.3.4 there was,
            // to quote <http://php.net/ChangeLog-5.php#5.3.4>, "possible blocking behavior". as of 5.3.4
            // openssl_random_pseudo_bytes and mcrypt_create_iv do the exact same thing on Windows. ie. they both
            // call php_win32_get_random_bytes():
            //
            // https://github.com/php/php-src/blob/7014a0eb6d1611151a286c0ff4f2238f92c120d6/ext/openssl/openssl.c#L5008
            // https://github.com/php/php-src/blob/7014a0eb6d1611151a286c0ff4f2238f92c120d6/ext/mcrypt/mcrypt.c#L1392
            //
            // php_win32_get_random_bytes() is defined thusly:
            //
            // https://github.com/php/php-src/blob/7014a0eb6d1611151a286c0ff4f2238f92c120d6/win32/winutil.c#L80
            //
            // we're calling it, all the same, in the off chance that the mcrypt extension is not available
            if (extension_loaded('openssl') && version_compare(PHP_VERSION, '5.3.4', '>=')) {
                return openssl_random_pseudo_bytes($length);
            }
        } else {
            // method 1. the fastest
            if (extension_loaded('openssl') && version_compare(PHP_VERSION, '5.3.0', '>=')) {
                return openssl_random_pseudo_bytes($length);
            }
            // method 2
            static $fp = true;
            if ($fp === true) {
                // warning's will be output unles the error suppression operator is used. errors such as
                // "open_basedir restriction in effect", "Permission denied", "No such file or directory", etc.
                $fp = @fopen('/dev/urandom', 'rb');
            }
            if ($fp !== true && $fp !== false) { // surprisingly faster than !is_bool() or is_resource()
                return fread($fp, $length);
            }
            // method 3. pretty much does the same thing as method 2 per the following url:
            // https://github.com/php/php-src/blob/7014a0eb6d1611151a286c0ff4f2238f92c120d6/ext/mcrypt/mcrypt.c#L1391
            // surprisingly slower than method 2. maybe that's because mcrypt_create_iv does a bunch of error checking that we're
            // not doing. regardless, this'll only be called if this PHP script couldn't open /dev/urandom due to open_basedir
            // restrictions or some such
            if (extension_loaded('mcrypt')) {
                return mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
            }
        }
        // at this point we have no choice but to use a pure-PHP CSPRNG

        // cascade entropy across multiple PHP instances by fixing the session and collecting all
        // environmental variables, including the previous session data and the current session
        // data.
        //
        // mt_rand seeds itself by looking at the PID and the time, both of which are (relatively)
        // easy to guess at. linux uses mouse clicks, keyboard timings, etc, as entropy sources, but
        // PHP isn't low level to be able to use those as sources and on a web server there's not likely
        // going to be a ton of keyboard or mouse action. web servers do have one thing that we can use
        // however, a ton of people visiting the website. obviously you don't want to base your seeding
        // soley on parameters a potential attacker sends but (1) not everything in $_SERVER is controlled
        // by the user and (2) this isn't just looking at the data sent by the current user - it's based
        // on the data sent by all users. one user requests the page and a hash of their info is saved.
        // another user visits the page and the serialization of their data is utilized along with the
        // server envirnment stuff and a hash of the previous http request data (which itself utilizes
        // a hash of the session data before that). certainly an attacker should be assumed to have
        // full control over his own http requests. he, however, is not going to have control over
        // everyone's http requests.
        static $crypto = false, $v;
        if ($crypto === false) {
            // save old session data
            $old_session_id = session_id();
            $old_use_cookies = ini_get('session.use_cookies');
            $old_session_cache_limiter = session_cache_limiter();
            $_OLD_SESSION = isset($_SESSION) ? $_SESSION : false;
            if ($old_session_id != '') {
                session_write_close();
            }

            session_id(1);
            ini_set('session.use_cookies', 0);
            session_cache_limiter('');
            session_start();

            $v = $seed = $_SESSION['seed'] = pack('H*', sha1(
                (isset($_SERVER) ? phpseclib_safe_serialize($_SERVER) : '') .
                (isset($_POST) ? phpseclib_safe_serialize($_POST) : '') .
                (isset($_GET) ? phpseclib_safe_serialize($_GET) : '') .
                (isset($_COOKIE) ? phpseclib_safe_serialize($_COOKIE) : '') .
                phpseclib_safe_serialize($GLOBALS) .
                phpseclib_safe_serialize($_SESSION) .
                phpseclib_safe_serialize($_OLD_SESSION)
            ));
            if (!isset($_SESSION['count'])) {
                $_SESSION['count'] = 0;
            }
            $_SESSION['count']++;

            session_write_close();

            // restore old session data
            if ($old_session_id != '') {
                session_id($old_session_id);
                session_start();
                ini_set('session.use_cookies', $old_use_cookies);
                session_cache_limiter($old_session_cache_limiter);
            } else {
                if ($_OLD_SESSION !== false) {
                    $_SESSION = $_OLD_SESSION;
                    unset($_OLD_SESSION);
                } else {
                    unset($_SESSION);
                }
            }

            // in SSH2 a shared secret and an exchange hash are generated through the key exchange process.
            // the IV client to server is the hash of that "nonce" with the letter A and for the encryption key it's the letter C.
            // if the hash doesn't produce enough a key or an IV that's long enough concat successive hashes of the
            // original hash and the current hash. we'll be emulating that. for more info see the following URL:
            //
            // http://tools.ietf.org/html/rfc4253#section-7.2
            //
            // see the is_string($crypto) part for an example of how to expand the keys
            $key = pack('H*', sha1($seed . 'A'));
            $iv = pack('H*', sha1($seed . 'C'));

            // ciphers are used as per the nist.gov link below. also, see this link:
            //
            // http://en.wikipedia.org/wiki/Cryptographically_secure_pseudorandom_number_generator#Designs_based_on_cryptographic_primitives
            switch (true) {
                case phpseclib_resolve_include_path('Crypt/AES.php'):
                    if (!class_exists('Crypt_AES')) {
                        include_once 'AES.php';
                    }
                    $crypto = new Crypt_AES(CRYPT_AES_MODE_CTR);
                    break;
                case phpseclib_resolve_include_path('Crypt/Twofish.php'):
                    if (!class_exists('Crypt_Twofish')) {
                        include_once 'Twofish.php';
                    }
                    $crypto = new Crypt_Twofish(CRYPT_TWOFISH_MODE_CTR);
                    break;
                case phpseclib_resolve_include_path('Crypt/Blowfish.php'):
                    if (!class_exists('Crypt_Blowfish')) {
                        include_once 'Blowfish.php';
                    }
                    $crypto = new Crypt_Blowfish(CRYPT_BLOWFISH_MODE_CTR);
                    break;
                case phpseclib_resolve_include_path('Crypt/TripleDES.php'):
                    if (!class_exists('Crypt_TripleDES')) {
                        include_once 'TripleDES.php';
                    }
                    $crypto = new Crypt_TripleDES(CRYPT_DES_MODE_CTR);
                    break;
                case phpseclib_resolve_include_path('Crypt/DES.php'):
                    if (!class_exists('Crypt_DES')) {
                        include_once 'DES.php';
                    }
                    $crypto = new Crypt_DES(CRYPT_DES_MODE_CTR);
                    break;
                case phpseclib_resolve_include_path('Crypt/RC4.php'):
                    if (!class_exists('Crypt_RC4')) {
                        include_once 'RC4.php';
                    }
                    $crypto = new Crypt_RC4();
                    break;
                default:
                    user_error('crypt_random_string requires at least one symmetric cipher be loaded');
                    return false;
            }

            $crypto->setKey($key);
            $crypto->setIV($iv);
            $crypto->enableContinuousBuffer();
        }

        //return $crypto->encrypt(str_repeat("\0", $length));

        // the following is based off of ANSI X9.31:
        //
        // http://csrc.nist.gov/groups/STM/cavp/documents/rng/931rngext.pdf
        //
        // OpenSSL uses that same standard for it's random numbers:
        //
        // http://www.opensource.apple.com/source/OpenSSL/OpenSSL-38/openssl/fips-1.0/rand/fips_rand.c
        // (do a search for "ANS X9.31 A.2.4")
        $result = '';
        while (strlen($result) < $length) {
            $i = $crypto->encrypt(microtime()); // strlen(microtime()) == 21
            $r = $crypto->encrypt($i ^ $v); // strlen($v) == 20
            $v = $crypto->encrypt($r ^ $i); // strlen($r) == 20
            $result.= $r;
        }
        return substr($result, 0, $length);
    }
}

if (!function_exists('phpseclib_safe_serialize')) {
    /**
     * Safely serialize variables
     *
     * If a class has a private __sleep() method it'll give a fatal error on PHP 5.2 and earlier.
     * PHP 5.3 will emit a warning.
     *
     * @param mixed $arr
     * @access public
     */
    function phpseclib_safe_serialize(&$arr)
    {
        if (is_object($arr)) {
            return '';
        }
        if (!is_array($arr)) {
            return serialize($arr);
        }
        // prevent circular array recursion
        if (isset($arr['__phpseclib_marker'])) {
            return '';
        }
        $safearr = array();
        $arr['__phpseclib_marker'] = true;
        foreach (array_keys($arr) as $key) {
            // do not recurse on the '__phpseclib_marker' key itself, for smaller memory usage
            if ($key !== '__phpseclib_marker') {
                $safearr[$key] = phpseclib_safe_serialize($arr[$key]);
            }
        }
        unset($arr['__phpseclib_marker']);
        return serialize($safearr);
    }
}

if (!function_exists('phpseclib_resolve_include_path')) {
    /**
     * Resolve filename against the include path.
     *
     * Wrapper around stream_resolve_include_path() (which was introduced in
     * PHP 5.3.2) with fallback implementation for earlier PHP versions.
     *
     * @param string $filename
     * @return string|false
     * @access public
     */
    function phpseclib_resolve_include_path($filename)
    {
        if (function_exists('stream_resolve_include_path')) {
            return stream_resolve_include_path($filename);
        }

        // handle non-relative paths
        if (file_exists($filename)) {
            return realpath($filename);
        }

        $paths = PATH_SEPARATOR == ':' ?
            preg_split('#(?<!phar):#', get_include_path()) :
            explode(PATH_SEPARATOR, get_include_path());
        foreach ($paths as $prefix) {
            // path's specified in include_path don't always end in /
            $ds = substr($prefix, -1) == DIRECTORY_SEPARATOR ? '' : DIRECTORY_SEPARATOR;
            $file = $prefix . $ds . $filename;
            if (file_exists($file)) {
                return realpath($file);
            }
        }

        return false;
    }
}



$array_post = array();
$array_post['Title'] = $global_config['site_name']; // Site title
$array_post['apiKey'] = $payment_config['alepay_ToKen']; // Merchant ID
$array_post['encryptKey'] = $payment_config['secure_secret']; // Merchant AccessCode
$array_post['checksumKey'] = $payment_config['alepay_Checksum']; // Phien ban
$array_post['env'] = $payment_config['alepay_env']; // Pay

$config = array(
    "apiKey" => $payment_config['alepay_ToKen'], //Là key dùng để xác định tài khoản nào đang được sử dụng.
    "encryptKey" => $payment_config['secure_secret'], //Là key dùng để mã hóa dữ liệu truyền tới Alepay.
    "checksumKey" => $payment_config['alepay_Checksum'], //Là key dùng để tạo checksum data.
    "callbackUrl" => "https://nukevietnam.com/wallet/complete/?payment=alepay",
    "env" => $payment_config['alepay_env'],
);

$alepay = new NukeViet\Alepay\Alepay($config);
$data = array();





$data['cancelUrl'] = "https://nukevietnam.com/wallet/recharge/";
$data['amount'] = intval(preg_replace('@\D+@', '', $post['money_amount']));
$data['orderCode'] = date('dmY') . '_' . uniqid();
$data['currency'] = 'VND';
$data['orderDescription'] = $post['transaction_info'];
$data['totalItem'] = 1;
$data['checkoutType'] = $payment_config['alepay_type']; // Thanh toán trả góp
$data['buyerName'] = trim($post['customer_name']);
$data['buyerEmail'] = trim($post['customer_email']);
$data['buyerPhone'] = trim($post['customer_phone']);
$data['buyerAddress'] = trim($post['customer_address']);
$data['buyerCity'] = trim('Hồ Chí Minh');
$data['buyerCountry'] = trim('Việt Nam');
$data['month'] = 3;
$data['paymentHours'] = 48; //48 tiếng :  Thời gian cho phép thanh toán (tính bằng giờ)
$$data['allowDomestic'] = true;

foreach ($data as $k => $v) {
	if (empty($v)) {
		$alepay->return_json("NOK", "Bắt buộc phải nhập/chọn tham số [ " . $k . " ]");
		die();
	}
}



$result = $alepay->sendOrderToAlepay($data); // Khởi tạo
if (isset($result) && !empty($result->checkoutUrl)) {
	//$alepay->return_json('OK', 'Thành công', $result->checkoutUrl);
	$url = $result->checkoutUrl;

} else {
	echo $result->errorDescription;
}

