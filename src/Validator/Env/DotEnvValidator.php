<?php


namespace Logistio\Symmetry\Validator\Env;


class DotEnvValidator
{
    /**
     * @var array
     */
    private $requiredVariables;

    /**
     * @var array
     */
    private $requiredValues;

    /**
     * DotEnvValidator constructor.
     * @param array $requiredVariables
     * @param array $requiredValues
     */
    public function __construct($requiredVariables = [], $requiredValues = [])
    {
        $this->requiredVariables = $requiredVariables;

        $this->requiredValues = $requiredValues;
    }

    /**
     * @throws \Exception
     */
    public function validate()
    {
        $this->validateRequiredVariables();

        $this->validateRequiredValues();
    }

    private function validateRequiredValues()
    {
        $invalidValues = [];

        foreach ($this->requiredValues as $variableName => $requiredValue) {

            $actualValue = env($variableName);

            if ($actualValue != $requiredValue) {
                $invalidValues[] = [
                    'variable' => $variableName,
                    'required_value' => $requiredValue,
                    'actual_value' => $actualValue
                ];
            }
        }

        if (count($invalidValues)) {
            throw new \Exception($this->makeMessageForInvalidRequiredValues($invalidValues));
        }
    }

    /**
     * @throws \Exception
     */
    private function validateRequiredVariables()
    {
        $missingVariables = [];

        foreach ($this->requiredVariables as $requiredVariable) {

            if (!$this->isEnvVariableSet($requiredVariable)) {
                $missingVariables[] = $requiredVariable;
            }
        }

        if (count($missingVariables)) {
            throw new \Exception(
                "The following variables are missing in the .env file: " . implode(",", $missingVariables)
            );
        }
    }

    /**
     * @param array $invalidValues
     * @return string
     */
    private function makeMessageForInvalidRequiredValues(array $invalidValues)
    {
        $message = "The following variables do not have the required values.";

        foreach ($invalidValues as $invalidValue) {

            $variable = $invalidValue['variable'];
            $requiredValue = $invalidValue['required_value'];
            $actualValue = $invalidValue['actual_value'];

            $message .= " - [ Variable: {$variable}, Required: ${requiredValue}, Actual: ${actualValue}.";
        }

        return $message;
    }

    /**
     * @param $variable
     * @return bool
     */
    private function isEnvVariableSet($variable)
    {
        return env($variable) !== null && env($variable) !== "";
    }
}