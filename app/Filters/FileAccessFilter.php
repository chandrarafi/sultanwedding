<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class FileAccessFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip processing for non-GET requests
        if ($request->getMethod() !== 'get') {
            return;
        }

        // Use getPath() method on the URI object properly
        $path = $request->getUri()->getPath();

        // Check if this is an uploads request
        if (strpos($path, 'uploads/') === 0) {
            // Check first in writable/uploads
            $writablePath = WRITEPATH . $path;
            // Then check in public/uploads
            $publicPath = ROOTPATH . 'public/' . $path;

            // Determine which path to use
            if (file_exists($writablePath) && is_file($writablePath)) {
                $fullPath = $writablePath;
                $uploadsPath = realpath(WRITEPATH . 'uploads/');
            } elseif (file_exists($publicPath) && is_file($publicPath)) {
                $fullPath = $publicPath;
                $uploadsPath = realpath(ROOTPATH . 'public/uploads/');
            } else {
                // File not found in either location
                return service('response')->setStatusCode(404, 'File not found');
            }

            // Security check - ensure file is within uploads directory
            $realPath = realpath($fullPath);

            if (strpos($realPath, $uploadsPath) !== 0) {
                return service('response')->setStatusCode(403, 'Access denied');
            }

            // Serve the file
            $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
            $filename = basename($fullPath);

            $response = service('response');
            $response->setStatusCode(200)
                ->setContentType($mime)
                ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                ->setHeader('Cache-Control', 'max-age=604800') // Cache for a week
                ->setBody(file_get_contents($fullPath));

            return $response;
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
