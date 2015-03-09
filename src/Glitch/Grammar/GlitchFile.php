<?php

namespace Glitch\Grammar;

use Glitch\Grammar\Tree\AddListenerNode;
use Glitch\Grammar\Tree\ActionNode;
use Glitch\Grammar\Tree\FireNode;
use Glitch\Grammar\Tree\ProgramNode;
use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\RemoveListenerNode;
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

        $_position2 = $this->position;
        $_cut3 = $this->cut;

        $this->cut = false;
        $_success = $this->parseFireStatement();

        if (!$_success && !$this->cut) {
            $this->position = $_position2;

            $_success = $this->parseAddListenerStatement();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position2;

            $_success = $this->parseRemoveListenerStatement();
        }

        $this->cut = $_cut3;

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

        $_value4 = array();

        $_success = $this->parseExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value4[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value4[] = $this->value;

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
            $_value4[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value4[] = $this->value;

            $_success = $this->parseExpression();

            if ($_success) {
                $right = $this->value;
            }
        }

        if ($_success) {
            $_value4[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value4[] = $this->value;

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
            $_value4[] = $this->value;

            $this->value = $_value4;
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

    protected function parseAddListenerStatement()
    {
        $_position = $this->position;

        if (isset($this->cache['AddListenerStatement'][$_position])) {
            $_success = $this->cache['AddListenerStatement'][$_position]['success'];
            $this->position = $this->cache['AddListenerStatement'][$_position]['position'];
            $this->value = $this->cache['AddListenerStatement'][$_position]['value'];

            return $_success;
        }

        $_value5 = array();

        $_success = $this->parseExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value5[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value5[] = $this->value;

            if (substr($this->string, $this->position, strlen("+=")) === "+=") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("+="));
                $this->position += strlen("+=");
            } else {
                $_success = false;

                $this->report($this->position, '"+="');
            }
        }

        if ($_success) {
            $_value5[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value5[] = $this->value;

            $_success = $this->parseExpression();

            if ($_success) {
                $right = $this->value;
            }
        }

        if ($_success) {
            $_value5[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value5[] = $this->value;

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
            $_value5[] = $this->value;

            $this->value = $_value5;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$left, &$right) {
                return new AddListenerNode($left, $right);
            });
        }

        $this->cache['AddListenerStatement'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'AddListenerStatement');
        }

        return $_success;
    }

    protected function parseRemoveListenerStatement()
    {
        $_position = $this->position;

        if (isset($this->cache['RemoveListenerStatement'][$_position])) {
            $_success = $this->cache['RemoveListenerStatement'][$_position]['success'];
            $this->position = $this->cache['RemoveListenerStatement'][$_position]['position'];
            $this->value = $this->cache['RemoveListenerStatement'][$_position]['value'];

            return $_success;
        }

        $_value6 = array();

        $_success = $this->parseExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value6[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value6[] = $this->value;

            if (substr($this->string, $this->position, strlen("-=")) === "-=") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("-="));
                $this->position += strlen("-=");
            } else {
                $_success = false;

                $this->report($this->position, '"-="');
            }
        }

        if ($_success) {
            $_value6[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value6[] = $this->value;

            $_success = $this->parseExpression();

            if ($_success) {
                $right = $this->value;
            }
        }

        if ($_success) {
            $_value6[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value6[] = $this->value;

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
            $_value6[] = $this->value;

            $this->value = $_value6;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$left, &$right) {
                return new RemoveListenerNode($left, $right);
            });
        }

        $this->cache['RemoveListenerStatement'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RemoveListenerStatement');
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

        $_position7 = $this->position;
        $_cut8 = $this->cut;

        $this->cut = false;
        $_success = $this->parseActionLiteral();

        if (!$_success && !$this->cut) {
            $this->position = $_position7;

            $_success = $this->parseReferenceExpression();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position7;

            $_success = $this->parseStringLiteral();
        }

        $this->cut = $_cut8;

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

        $_position16 = $this->position;

        $_value15 = array();

        if (substr($this->string, $this->position, strlen("\"")) === "\"") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("\""));
            $this->position += strlen("\"");
        } else {
            $_success = false;

            $this->report($this->position, '"\\""');
        }

        if ($_success) {
            $_value15[] = $this->value;

            $_value13 = array();
            $_cut14 = $this->cut;

            while (true) {
                $_position12 = $this->position;

                $this->cut = false;
                $_position10 = $this->position;
                $_cut11 = $this->cut;

                $this->cut = false;
                if (preg_match('/^[^\\\\"]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position10;

                    $_value9 = array();

                    if (substr($this->string, $this->position, strlen("\\")) === "\\") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen("\\"));
                        $this->position += strlen("\\");
                    } else {
                        $_success = false;

                        $this->report($this->position, '"\\\\"');
                    }

                    if ($_success) {
                        $_value9[] = $this->value;

                        if ($this->position < strlen($this->string)) {
                            $_success = true;
                            $this->value = substr($this->string, $this->position, 1);
                            $this->position += 1;
                        } else {
                            $_success = false;
                        }
                    }

                    if ($_success) {
                        $_value9[] = $this->value;

                        $this->value = $_value9;
                    }
                }

                $this->cut = $_cut11;

                if (!$_success) {
                    break;
                }

                $_value13[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position12;
                $this->value = $_value13;
            }

            $this->cut = $_cut14;
        }

        if ($_success) {
            $_value15[] = $this->value;

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
            $_value15[] = $this->value;

            $this->value = $_value15;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position16, $this->position - $_position16));
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

    protected function parseActionLiteral()
    {
        $_position = $this->position;

        if (isset($this->cache['ActionLiteral'][$_position])) {
            $_success = $this->cache['ActionLiteral'][$_position]['success'];
            $this->position = $this->cache['ActionLiteral'][$_position]['position'];
            $this->value = $this->cache['ActionLiteral'][$_position]['value'];

            return $_success;
        }

        $_value17 = array();

        $_success = $this->parseParameterList();

        if ($_success) {
            $parameters = $this->value;
        }

        if ($_success) {
            $_value17[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value17[] = $this->value;

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
            $_value17[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value17[] = $this->value;

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
            $_value17[] = $this->value;

            $_success = $this->parseStatementList();

            if ($_success) {
                $statements = $this->value;
            }
        }

        if ($_success) {
            $_value17[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value17[] = $this->value;

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
            $_value17[] = $this->value;

            $this->value = $_value17;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$parameters, &$statements) {
                return new ActionNode($parameters, $statements);
            });
        }

        $this->cache['ActionLiteral'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ActionLiteral');
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

        $_value19 = array();
        $_cut20 = $this->cut;

        while (true) {
            $_position18 = $this->position;

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

            $_value19[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position18;
            $this->value = $_value19;
        }

        $this->cut = $_cut20;

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

        $_position25 = $this->position;

        $_value24 = array();

        if (preg_match('/^[A-Za-z_]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value24[] = $this->value;

            $_value22 = array();
            $_cut23 = $this->cut;

            while (true) {
                $_position21 = $this->position;

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

                $_value22[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position21;
                $this->value = $_value22;
            }

            $this->cut = $_cut23;
        }

        if ($_success) {
            $_value24[] = $this->value;

            $this->value = $_value24;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position25, $this->position - $_position25));
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

        $_value28 = array();
        $_cut29 = $this->cut;

        while (true) {
            $_position27 = $this->position;

            $this->cut = false;
            $_value26 = array();

            $_success = $this->parse_();

            if ($_success) {
                $_value26[] = $this->value;

                $_success = $this->parseStatement();

                if ($_success) {
                    $statement = $this->value;
                }
            }

            if ($_success) {
                $_value26[] = $this->value;

                $this->value = $_value26;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$statement) {
                    return $statement;
                });
            }

            if (!$_success) {
                break;
            }

            $_value28[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position27;
            $this->value = $_value28;
        }

        $this->cut = $_cut29;

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