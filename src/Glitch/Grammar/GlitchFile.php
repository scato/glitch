<?php

namespace Glitch\Grammar;

use Glitch\Grammar\Tree\EventNode;
use Glitch\Grammar\Tree\FireNode;
use Glitch\Grammar\Tree\ProgramNode;
use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\StringNode;

class GlitchFile
{
    protected $string;
    protected $position;
    protected $value;
    protected $cache;
    protected $cut;
    protected $errors;
    protected $warnings;

    protected function parseProgram()
    {
        $_position = $this->position;

        if (isset($this->cache['Program'][$_position])) {
            $_success = $this->cache['Program'][$_position]['success'];
            $this->position = $this->cache['Program'][$_position]['position'];
            $this->value = $this->cache['Program'][$_position]['value'];

            return $_success;
        }

        $_value1 = array();

        $_success = $this->parseStatementList();

        if ($_success) {
            $statements = $this->value;
        }

        if ($_success) {
            $_value1[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value1[] = $this->value;

            $this->value = $_value1;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$statements) {
                return new ProgramNode($statements);
            });
        }

        $this->cache['Program'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'Program');
        }

        return $_success;
    }

    protected function parseStatement()
    {
        $_position = $this->position;

        if (isset($this->cache['Statement'][$_position])) {
            $_success = $this->cache['Statement'][$_position]['success'];
            $this->position = $this->cache['Statement'][$_position]['position'];
            $this->value = $this->cache['Statement'][$_position]['value'];

            return $_success;
        }

        $_success = $this->parseFireStatement();

        $this->cache['Statement'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'Statement');
        }

        return $_success;
    }

    protected function parseFireStatement()
    {
        $_position = $this->position;

        if (isset($this->cache['FireStatement'][$_position])) {
            $_success = $this->cache['FireStatement'][$_position]['success'];
            $this->position = $this->cache['FireStatement'][$_position]['position'];
            $this->value = $this->cache['FireStatement'][$_position]['value'];

            return $_success;
        }

        $_value2 = array();

        $_success = $this->parseExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value2[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value2[] = $this->value;

            if (substr($this->string, $this->position, strlen("!")) === "!") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("!"));
                $this->position += strlen("!");
            } else {
                $_success = false;

                $this->report($this->position, '"!"');
            }
        }

        if ($_success) {
            $_value2[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value2[] = $this->value;

            $_success = $this->parseExpression();

            if ($_success) {
                $right = $this->value;
            }
        }

        if ($_success) {
            $_value2[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value2[] = $this->value;

            if (substr($this->string, $this->position, strlen(";")) === ";") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen(";"));
                $this->position += strlen(";");
            } else {
                $_success = false;

                $this->report($this->position, '";"');
            }
        }

        if ($_success) {
            $_value2[] = $this->value;

            $this->value = $_value2;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$left, &$right) {
                return new FireNode($left, $right);
            });
        }

        $this->cache['FireStatement'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FireStatement');
        }

        return $_success;
    }

    protected function parseExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['Expression'][$_position])) {
            $_success = $this->cache['Expression'][$_position]['success'];
            $this->position = $this->cache['Expression'][$_position]['position'];
            $this->value = $this->cache['Expression'][$_position]['value'];

            return $_success;
        }

        $_position3 = $this->position;
        $_cut4 = $this->cut;

        $this->cut = false;
        $_success = $this->parseEventLiteral();

        if (!$_success && !$this->cut) {
            $this->position = $_position3;

            $_success = $this->parseReferenceExpression();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position3;

            $_success = $this->parseStringLiteral();
        }

        $this->cut = $_cut4;

        $this->cache['Expression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'Expression');
        }

        return $_success;
    }

    protected function parseReferenceExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['ReferenceExpression'][$_position])) {
            $_success = $this->cache['ReferenceExpression'][$_position]['success'];
            $this->position = $this->cache['ReferenceExpression'][$_position]['position'];
            $this->value = $this->cache['ReferenceExpression'][$_position]['value'];

            return $_success;
        }

        $_success = $this->parseIdentifier();

        if ($_success) {
            $value = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$value) {
                return new ReferenceNode($value);
            });
        }

        $this->cache['ReferenceExpression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ReferenceExpression');
        }

        return $_success;
    }

    protected function parseStringLiteral()
    {
        $_position = $this->position;

        if (isset($this->cache['StringLiteral'][$_position])) {
            $_success = $this->cache['StringLiteral'][$_position]['success'];
            $this->position = $this->cache['StringLiteral'][$_position]['position'];
            $this->value = $this->cache['StringLiteral'][$_position]['value'];

            return $_success;
        }

        $_position12 = $this->position;

        $_value11 = array();

        if (substr($this->string, $this->position, strlen("\"")) === "\"") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("\""));
            $this->position += strlen("\"");
        } else {
            $_success = false;

            $this->report($this->position, '"\\""');
        }

        if ($_success) {
            $_value11[] = $this->value;

            $_value9 = array();
            $_cut10 = $this->cut;

            while (true) {
                $_position8 = $this->position;

                $this->cut = false;
                $_position6 = $this->position;
                $_cut7 = $this->cut;

                $this->cut = false;
                if (preg_match('/^[^\\\\"]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position6;

                    $_value5 = array();

                    if (substr($this->string, $this->position, strlen("\\")) === "\\") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen("\\"));
                        $this->position += strlen("\\");
                    } else {
                        $_success = false;

                        $this->report($this->position, '"\\\\"');
                    }

                    if ($_success) {
                        $_value5[] = $this->value;

                        if ($this->position < strlen($this->string)) {
                            $_success = true;
                            $this->value = substr($this->string, $this->position, 1);
                            $this->position += 1;
                        } else {
                            $_success = false;
                        }
                    }

                    if ($_success) {
                        $_value5[] = $this->value;

                        $this->value = $_value5;
                    }
                }

                $this->cut = $_cut7;

                if (!$_success) {
                    break;
                }

                $_value9[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position8;
                $this->value = $_value9;
            }

            $this->cut = $_cut10;
        }

        if ($_success) {
            $_value11[] = $this->value;

            if (substr($this->string, $this->position, strlen("\"")) === "\"") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("\""));
                $this->position += strlen("\"");
            } else {
                $_success = false;

                $this->report($this->position, '"\\""');
            }
        }

        if ($_success) {
            $_value11[] = $this->value;

            $this->value = $_value11;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position12, $this->position - $_position12));
        }

        if ($_success) {
            $value = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$value) {
                return new StringNode($value);
            });
        }

        $this->cache['StringLiteral'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'StringLiteral');
        }

        return $_success;
    }

    protected function parseEventLiteral()
    {
        $_position = $this->position;

        if (isset($this->cache['EventLiteral'][$_position])) {
            $_success = $this->cache['EventLiteral'][$_position]['success'];
            $this->position = $this->cache['EventLiteral'][$_position]['position'];
            $this->value = $this->cache['EventLiteral'][$_position]['value'];

            return $_success;
        }

        $_value13 = array();

        $_success = $this->parseParameterList();

        if ($_success) {
            $parameters = $this->value;
        }

        if ($_success) {
            $_value13[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value13[] = $this->value;

            if (substr($this->string, $this->position, strlen("=>")) === "=>") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("=>"));
                $this->position += strlen("=>");
            } else {
                $_success = false;

                $this->report($this->position, '"=>"');
            }
        }

        if ($_success) {
            $_value13[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value13[] = $this->value;

            if (substr($this->string, $this->position, strlen("{")) === "{") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("{"));
                $this->position += strlen("{");
            } else {
                $_success = false;

                $this->report($this->position, '"{"');
            }
        }

        if ($_success) {
            $_value13[] = $this->value;

            $_success = $this->parseStatementList();

            if ($_success) {
                $statements = $this->value;
            }
        }

        if ($_success) {
            $_value13[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value13[] = $this->value;

            if (substr($this->string, $this->position, strlen("}")) === "}") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("}"));
                $this->position += strlen("}");
            } else {
                $_success = false;

                $this->report($this->position, '"}"');
            }
        }

        if ($_success) {
            $_value13[] = $this->value;

            $this->value = $_value13;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$parameters, &$statements) {
                return new EventNode($parameters, $statements);
            });
        }

        $this->cache['EventLiteral'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'EventLiteral');
        }

        return $_success;
    }

    protected function parse_()
    {
        $_position = $this->position;

        if (isset($this->cache['_'][$_position])) {
            $_success = $this->cache['_'][$_position]['success'];
            $this->position = $this->cache['_'][$_position]['position'];
            $this->value = $this->cache['_'][$_position]['value'];

            return $_success;
        }

        $_value15 = array();
        $_cut16 = $this->cut;

        while (true) {
            $_position14 = $this->position;

            $this->cut = false;
            if (preg_match('/^[\\r\\t\\n ]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if (!$_success) {
                break;
            }

            $_value15[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position14;
            $this->value = $_value15;
        }

        $this->cut = $_cut16;

        $this->cache['_'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, '_');
        }

        return $_success;
    }

    protected function parseIdentifier()
    {
        $_position = $this->position;

        if (isset($this->cache['Identifier'][$_position])) {
            $_success = $this->cache['Identifier'][$_position]['success'];
            $this->position = $this->cache['Identifier'][$_position]['position'];
            $this->value = $this->cache['Identifier'][$_position]['value'];

            return $_success;
        }

        $_position21 = $this->position;

        $_value20 = array();

        if (preg_match('/^[A-Za-z_]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value20[] = $this->value;

            $_value18 = array();
            $_cut19 = $this->cut;

            while (true) {
                $_position17 = $this->position;

                $this->cut = false;
                if (preg_match('/^[A-Za-z0-9_]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success) {
                    break;
                }

                $_value18[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position17;
                $this->value = $_value18;
            }

            $this->cut = $_cut19;
        }

        if ($_success) {
            $_value20[] = $this->value;

            $this->value = $_value20;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position21, $this->position - $_position21));
        }

        $this->cache['Identifier'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'Identifier');
        }

        return $_success;
    }

    protected function parseStatementList()
    {
        $_position = $this->position;

        if (isset($this->cache['StatementList'][$_position])) {
            $_success = $this->cache['StatementList'][$_position]['success'];
            $this->position = $this->cache['StatementList'][$_position]['position'];
            $this->value = $this->cache['StatementList'][$_position]['value'];

            return $_success;
        }

        $_value24 = array();
        $_cut25 = $this->cut;

        while (true) {
            $_position23 = $this->position;

            $this->cut = false;
            $_value22 = array();

            $_success = $this->parse_();

            if ($_success) {
                $_value22[] = $this->value;

                $_success = $this->parseStatement();

                if ($_success) {
                    $statement = $this->value;
                }
            }

            if ($_success) {
                $_value22[] = $this->value;

                $this->value = $_value22;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$statement) {
                    return $statement;
                });
            }

            if (!$_success) {
                break;
            }

            $_value24[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position23;
            $this->value = $_value24;
        }

        $this->cut = $_cut25;

        $this->cache['StatementList'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'StatementList');
        }

        return $_success;
    }

    protected function parseParameterList()
    {
        $_position = $this->position;

        if (isset($this->cache['ParameterList'][$_position])) {
            $_success = $this->cache['ParameterList'][$_position]['success'];
            $this->position = $this->cache['ParameterList'][$_position]['position'];
            $this->value = $this->cache['ParameterList'][$_position]['value'];

            return $_success;
        }

        $_success = $this->parseIdentifier();

        if ($_success) {
            $parameter = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$parameter) {
                return [$parameter];
            });
        }

        $this->cache['ParameterList'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ParameterList');
        }

        return $_success;
    }

    private function line()
    {
        return count(explode("\n", substr($this->string, 0, $this->position)));
    }

    private function rest()
    {
        return '"' . substr($this->string, $this->position) . '"';
    }

    protected function report($position, $expecting)
    {
        if ($this->cut) {
            $this->errors[$position][] = $expecting;
        } else {
            $this->warnings[$position][] = $expecting;
        }
    }

    private function expecting()
    {
        if (!empty($this->errors)) {
            ksort($this->errors);

            return end($this->errors)[0];
        }

        ksort($this->warnings);

        return implode(', ', end($this->warnings));
    }

    public function parse($_string)
    {
        $this->string = $_string;
        $this->position = 0;
        $this->value = null;
        $this->cache = array();
        $this->cut = false;
        $this->errors = array();
        $this->warnings = array();

        $_success = $this->parseProgram();

        if (!$_success) {
            throw new \InvalidArgumentException("Syntax error, expecting {$this->expecting()} on line {$this->line()}");
        }

        if ($this->position < strlen($this->string)) {
            throw new \InvalidArgumentException("Syntax error, unexpected {$this->rest()} on line {$this->line()}");
        }

        return $this->value;
    }
}