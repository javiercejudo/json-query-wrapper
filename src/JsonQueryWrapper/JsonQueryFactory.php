<?php
/**
 * JSON Query Wrapper
 *
 * (The MIT license)
 * Copyright (c) 2016 Enrico Stahn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated * documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @package JsonQueryWrapper
 * @link    http://github.com/estahn/json-query-wrapper for the canonical source repository
 */

namespace JsonQueryWrapper;

use JsonQueryWrapper\DataProvider\File;
use JsonQueryWrapper\DataProvider\Text;
use Symfony\Component\Process\ProcessBuilder;

class JsonQueryFactory
{
    /**
     * Creates a JsonQuery object without data provider
     *
     * @return JsonQuery
     */
    public static function create()
    {
        return new JsonQuery(new ProcessBuilder(), new DataTypeMapper());
    }

    /**
     * Creates a JsonQuery object with data provider
     *
     * @param string $filenameOrText A path to a json file or json text
     * @return JsonQuery
     */
    public static function createWith($filenameOrText)
    {
        $provider = file_exists($filenameOrText) ? new File($filenameOrText) : new Text($filenameOrText);

        $jq = new JsonQuery(new ProcessBuilder(), new DataTypeMapper());
        $jq->setDataProvider($provider);

        return $jq;
    }
}
