<?php

namespace App\Converter;

use App\Entity\CharacterCp;
use App\Services\CharacterCp\CharacterCpProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class CharacterCpConverter implements ParamConverterInterface
{
    private CharacterCpProvider $characterCpProvider;
    private Security $security;
    public function __construct(CharacterCpProvider $characterCpProvider, Security $security)
    {
        $this->characterCpProvider = $characterCpProvider;
        $this->security = $security;
    }

    public function apply(Request $request, ParamConverter $configuration):bool
    {
        $iterationNumber = $request->get('iterationNumber');
        $user = $this->security->getUser();
        $class = $configuration->getClass();
        if (!$iterationNumber || CharacterCp::class !== $class) {
            return false;
        }
        $characterCp = $this->characterCpProvider->findCharacterCpByIterationNumberAndUser($iterationNumber, $user);
        if (!$characterCp) {
            return false;
        }
        $request->attributes->set($configuration->getName(), $characterCp);
        return true;
    }

    public function supports(ParamConverter $configuration):?string
    {
        return $configuration->getName() === 'characterCp';
    }
}