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

namespace Tests\JsonQueryWrapper;

use JsonQueryWrapper\DataProvider\Text;
use JsonQueryWrapper\DataTypeMapper;
use JsonQueryWrapper\JsonQuery;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class JsonQuery
 *
 * @package JsonQueryWrapper
 */
class JsonQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testCmdChange()
    {
        $this->markTestSkipped('WIP');

        $builder = new ProcessBuilder();

        $builder = $this
            ->getMockBuilder('Symfony\Component\Process\ProcessBuilder')
            ->disableOriginalConstructor()
            ->getMock()
            ->method('setPrefix')->with('foobar')->will($this->returnSelf());

        $test = json_encode(['Foo' => ['Bar' => 33]]);

        $jq = new JsonQuery($builder, new DataTypeMapper());
        $jq->setCmd('foobar');

        $this->assertTrue($jq->run('.Foo.Bar == 33'));
    }

    public function testSomething()
    {
        $this->markTestSkipped('WIP');

        $test = json_encode(['Foo' => ['Bar' => 33]]);

        $jq = new JsonQuery(new ProcessBuilder(), new DataTypeMapper());
        $jq->setCmd('foobar');
        $jq->setDataProvider(new Text($test));

        $this->assertTrue($jq->run('.Foo.Bar == 33'));
    }

    public function testFixProcessBuilderPileup()
    {
        $process1 = $this->getMockBuilder('Symfony\Component\Process\Process')
            ->disableOriginalConstructor()
            ->getMock();
        $process1->expects($this->once())->method('run');
        $process1->expects($this->once())->method('getOutput')->will($this->returnValue(33));

        $process2 = $this->getMockBuilder('Symfony\Component\Process\Process')
            ->disableOriginalConstructor()
            ->getMock();
        $process2->expects($this->once())->method('run');
        $process2->expects($this->once())->method('getOutput')->will($this->returnValue(33));

        $processBuilder = $this->getMockBuilder('Symfony\Component\Process\ProcessBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $processBuilder->expects($this->any())->method('setPrefix')->will($this->returnSelf());
        $processBuilder->expects($this->any())->method('setArguments')->will($this->returnSelf());
        $processBuilder->expects($this->any())->method('getProcess')
                ->will($this->onConsecutiveCalls($process1, $process2));

        $dataTypeMapper = $this->getMockBuilder('JsonQueryWrapper\DataTypeMapper')
            ->disableOriginalConstructor()
            ->getMock();
        $dataTypeMapper->expects($this->any())->method('map')->will($this->onConsecutiveCalls(33, 33));

        $provider = $this->getMockBuilder('JsonQueryWrapper\DataProvider\Text')
            ->disableOriginalConstructor()
            ->getMock();

        $jsonQuery = new JsonQuery($processBuilder, $dataTypeMapper);
        $jsonQuery->setDataProvider($provider);

        $this->assertEquals(33, $jsonQuery->run('.Foo.Bar'));
        $this->assertEquals(33, $jsonQuery->run('.Foo.Bar'));
    }
}
