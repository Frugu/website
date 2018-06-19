<?php

namespace App\Entity\Auth;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;

class UserProvider implements OAuthAwareUserProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $data = $response->getData();

        /**
         * array(7) {
         *  ["CharacterID"]=> int(91901482)
         *  ["CharacterName"]=> string(8) "Mealtime"
         *  ["ExpiresOn"]=> string(19) "2018-06-19T12:36:54"
         *  ["Scopes"]=> string(0) ""
         *  ["TokenType"]=> string(9) "Character"
         *  ["CharacterOwnerHash"]=> string(28) "kejN8pZQwW7QfG7u3PT1xE3zlQk="
         *  ["IntellectualProperty"]=> string(3) "EVE"
         * }
         */

        exit;
    }
}