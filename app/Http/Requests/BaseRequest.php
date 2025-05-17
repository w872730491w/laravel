<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Precognition;
use Illuminate\Validation\ValidationException;


class BaseRequest extends FormRequest
{
    /**
     * 当前场景
     * @var string|null
     */
    protected string|null $currentScene = null;

    /**
     * 场景
     * @var array<string, array<string>|string> Scene configurations
     */
    protected array $scenes = [];

    /**
     * 设置场景
     * @param $scene
     * @return $this
     */
    public function scene($scene): static
    {
        $this->currentScene = $scene;
        return $this;
    }


    /**
     * 验证方法
     * @param string $scene
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function validate($scene = ''): array
    {
        if ($scene) {
            $this->currentScene = $scene;
        }
        $this->handleValidate();
        return $this->validator->validated();
    }

    /**
     * 根据场景获取规则
     * @return array
     */
    public function getRules(): array
    {
        return $this->handleScene($this->container->call([$this, 'rules']));
    }

    /**
     * 场景验证
     * @param array $rule
     * @return array
     */
    protected function handleScene(array $rule): array
    {
        $sceneName = $this->currentScene;
        if ($sceneName) {
            $scene = $this->scenes;
            if (array_key_exists($sceneName, $scene)) {
                return $this->handleRule($scene[$sceneName], $rule);
            }
        }
        return $rule;
    }

    /**
     * 处理Rule
     * @param array $sceneRule
     * @param array $rule
     * @return array
     */
    private function handleRule(array $sceneRule, array $rule): array
    {
        $rules = [];
        foreach ($sceneRule as $key => $value) {
            if (is_numeric($key) && array_key_exists($value, $rule)) {
                $rules[$value] = $rule[$value];
            } else {
                $rules[$key] = $value;
            }
        }
        return $rules;
    }

    public function validateResolved()
    {
        //
    }

    /**
     * 覆盖设置 自定义验证器
     * @param ValidationFactory $factory
     * @return Validator
     */
    public function validator(ValidationFactory $factory): mixed
    {
        return $factory->make($this->validationData(), $this->getRules(), $this->messages(), $this->attributes());
    }

    /**
     * 最终验证方法
     * @throws AuthorizationException
     * @throws ValidationException
     */
    protected function handleValidate(): void
    {
        $this->prepareForValidation();

        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $instance = $this->getValidatorInstance();

        if ($this->isPrecognitive()) {
            $instance->after(Precognition::afterValidationHook($this));
        }

        if ($instance->fails()) {
            $this->failedValidation($instance);
        }

        $this->passedValidation();
    }
}
