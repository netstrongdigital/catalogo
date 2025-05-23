<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\Common\Proxy\Exception;

use UnexpectedValueException as BaseUnexpectedValueException;

/**
 * Proxy Unexpected Value Exception.
 *
 * @link   www.doctrine-project.org
 * @since  2.4
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class UnexpectedValueException extends BaseUnexpectedValueException implements ProxyException
{
    /**
     * @param string $proxyDirectory
     *
     * @return self
     */
    public static function proxyDirectoryNotWritable($proxyDirectory)
    {
        return new self(sprintf('Your proxy directory "%s" must be writable', $proxyDirectory));
    }

    /**
     * @param string          $className
     * @param string          $methodName
     * @param string          $parameterName
     * @param \Exception|null $previous
     *
     * @return self
     */
    public static function invalidParameterTypeHint(
        $className,
        $methodName,
        $parameterName,
        \Exception $previous = null
    ) {
        return new self(
            sprintf(
                'The type hint of parameter "%s" in method "%s" in class "%s" is invalid.',
                $parameterName,
                $methodName,
                $className
            ),
            0,
            $previous
        );
    }

    /**
     * @param $className
     * @param $methodName
     * @param \Exception|null $previous
     *
     * @return self
     */
    public static function invalidReturnTypeHint($className, $methodName, \Exception $previous = null)
    {
        return new self(
            sprintf(
                'The return type of method "%s" in class "%s" is invalid.',
                $methodName,
                $className
            ),
            0,
            $previous
        );
    }
}
