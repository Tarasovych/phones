<?php

namespace App\Consumer;

use App\Entity\Phone;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class ContactService implements ConsumerInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param AMQPMessage $msg The message
     * @return mixed false to reject and requeue, any other value to acknowledge
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute(AMQPMessage $msg)
    {
        $msg = \json_decode($msg->getBody(), 1);
        $user = new User();
        $user->setFirstName($msg['firstName']);
        $user->setLastName($msg['lastName']);

        foreach ($msg['phoneNumbers'] as $number) {
            $phone = new Phone();
            $phone->setNumber($number);
            $user->addPhone($phone);
            $this->em->persist($phone);
        }

        $this->em->persist($user);
        $this->em->flush();
    }
}