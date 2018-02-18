<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Redirects a request to another URL.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final since version 3.4
 */
class RedirectController
{
    private $router;
    private $httpPort;
    private $httpsPort;

    public function __construct(UrlGeneratorInterface $router = null, int $httpPort = null, int $httpsPort = null)
    {
        $this->router = $router;
        $this->httpPort = $httpPort;
        $this->httpsPort = $httpsPort;
    }

    /**
     * Redirects to another route with the given name.
     *
     * The response status code is 302 if the permanent parameter is false (default),
     * and 301 if the redirection is permanent.
     *
     * In case the route name is empty, the status code will be 404 when permanent is false
     * and 410 otherwise.
     *
     * @param Request    $request           The request instance
     * @param string     $route             The route name to redirect to
     * @param bool       $permanent         Whether the redirection is permanent
     * @param bool|array $ignoreAttributes  Whether to ignore attributes or an array of attributes to ignore
     * @param bool       $keepRequestMethod Wheter redirect action should keep HTTP request method
     *
     * @throws HttpException In case the route name is empty
     */
    public function redirectAction(Request $request, string $route, bool $permanent = false, $ignoreAttributes = false, bool $keepRequestMethod = false): Response
    {
        if ('' == $route) {
            throw new HttpException($permanent ? Response::HTTP_GONE : Response::HTTP_NOT_FOUND);
        }

        $attributes = array();
        if (false === $ignoreAttributes || is_array($ignoreAttributes)) {
            $attributes = $request->attributes->get('_route_params');
            unset($attributes['route'], $attributes['permanent'], $attributes['ignoreAttributes'], $attributes['keepRequestMethod']);
            if ($ignoreAttributes) {
                $attributes = array_diff_key($attributes, array_flip($ignoreAttributes));
            }
        }

        if ($keepRequestMethod) {
            $statusCode = $permanent ? Response::HTTP_PERMANENTLY_REDIRECT : Response::HTTP_TEMPORARY_REDIRECT;
        } else {
            @trigger_error('Since next major release redirect action will be made with 307/308 HTTP status codes', \E_USER_DEPRECATED);
            $statusCode = $permanent ? Response::HTTP_MOVED_PERMANENTLY : Response::HTTP_FOUND;
        }

        return new RedirectResponse($this->router->generate($route, $attributes, UrlGeneratorInterface::ABSOLUTE_URL), $statusCode);
    }

    /**
     * Redirects to a URL.
     *
     * The response status code is 302 if the permanent parameter is false (default),
     * and 301 if the redirection is permanent.
     *
     * In case the path is empty, the status code will be 404 when permanent is false
     * and 410 otherwise.
     *
     * @param Request     $request           The request instance
     * @param string      $path              The absolute path or URL to redirect to
     * @param bool        $permanent         Whether the redirect is permanent or not
     * @param string|null $scheme            The URL scheme (null to keep the current one)
     * @param int|null    $httpPort          The HTTP port (null to keep the current one for the same scheme or the default configured port)
     * @param int|null    $httpsPort         The HTTPS port (null to keep the current one for the same scheme or the default configured port)
     * @param bool        $keepRequestMethod Wheter redirect action should keep HTTP request method
     *
     * @throws HttpException In case the path is empty
     */
    public function urlRedirectAction(Request $request, string $path, bool $permanent = false, string $scheme = null, int $httpPort = null, int $httpsPort = null, bool $keepRequestMethod = false): Response
    {
        if ('' == $path) {
            throw new HttpException($permanent ? Response::HTTP_GONE : Response::HTTP_NOT_FOUND);
        }

        if ($keepRequestMethod) {
            $statusCode = $permanent ? Response::HTTP_PERMANENTLY_REDIRECT : Response::HTTP_TEMPORARY_REDIRECT;
        } else {
            @trigger_error('Since next major release redirect action will be made with 307/308 HTTP status codes', \E_USER_DEPRECATED);
            $statusCode = $permanent ? Response::HTTP_MOVED_PERMANENTLY : Response::HTTP_FOUND;
        }

        // redirect if the path is a full URL
        if (parse_url($path, PHP_URL_SCHEME)) {
            return new RedirectResponse($path, $statusCode);
        }

        if (null === $scheme) {
            $scheme = $request->getScheme();
        }

        $qs = $request->getQueryString();
        if ($qs) {
            if (false === strpos($path, '?')) {
                $qs = '?'.$qs;
            } else {
                $qs = '&'.$qs;
            }
        }

        $port = '';
        if ('http' === $scheme) {
            if (null === $httpPort) {
                if ('http' === $request->getScheme()) {
                    $httpPort = $request->getPort();
                } else {
                    $httpPort = $this->httpPort;
                }
            }

            if (null !== $httpPort && 80 != $httpPort) {
                $port = ":$httpPort";
            }
        } elseif ('https' === $scheme) {
            if (null === $httpsPort) {
                if ('https' === $request->getScheme()) {
                    $httpsPort = $request->getPort();
                } else {
                    $httpsPort = $this->httpsPort;
                }
            }

            if (null !== $httpsPort && 443 != $httpsPort) {
                $port = ":$httpsPort";
            }
        }

        $url = $scheme.'://'.$request->getHost().$port.$request->getBaseUrl().$path.$qs;

        return new RedirectResponse($url, $statusCode);
    }
}
