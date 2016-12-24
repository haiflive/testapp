<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;
    
    public function amLoggedInAs($user)
    {
        if (!Yii::$app->has('user')) {
            throw new ModuleException($this, 'User component is not loaded');
        }
        if ($user instanceof \yii\web\IdentityInterface) {
            $identity = $user;
        } else {
            // class name implementing IdentityInterface
            $identityClass = Yii::$app->user->identityClass;
            $identity = call_user_func([$identityClass, 'findIdentity'], $user);        }
        Yii::$app->user->login($identity);
    }
    
}
