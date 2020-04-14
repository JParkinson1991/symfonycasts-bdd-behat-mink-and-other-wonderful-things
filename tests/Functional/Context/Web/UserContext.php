<?php
/**
 * @file
 * UserContext.php
 */

namespace Tests\Functional\Context\Web;

use AppBundle\Entity\User;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Exception;

/**
 * Class UserContext
 *
 * Defines application features relating to users/accounts
 */
class UserContext extends BaseWebContext
{
    /**
     * Leverage symfony bootstrapping
     */
    use KernelDictionary;

    /**
     * Holds the currently logged in user
     *
     * @var \AppBundle\Entity\User
     */
    protected $currentUser;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * Returns the currently logged in user.
     *
     * Useful for contexts requiring knowledge of the currently logged in users
     * for their step definitions.
     *
     * @return \AppBundle\Entity\User
     *
     * @throws \Exception
     */
    public function getCurrentUser()
    {
        if ($this->currentUser === null) {
            throw new Exception('No user logged in');
        }

        return $this->currentUser;
    }

    /**
     * @Given there is an admin user :username with password :password
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function thereIsAnAdminUserWithPassword($username, $password)
    {
        return $this->createUser(
            $username,
            $password,
            array(
                'ROLE_ADMIN'
            )
        );
    }

    /**
     * @Given I am logged in as an admin
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iAmLoggedInAsAnAdmin()
    {
        $this->currentUser = $this->thereIsAnAdminUserWithPassword('admin', 'admin');

        $this->visitPath('/login');
        $this->getPage()->fillField('Username', 'admin');
        $this->getPage()->fillField('Password', 'admin');
        $this->getPage()->pressButton('Login');
    }

    /**
     * Creates a user in the database
     *
     * @param string $username
     * @param string $password
     * @param array $roles
     *
     * @return \AppBundle\Entity\User
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createUser($username, $password, $roles = array('ROLE_USER'))
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setRoles($roles);

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }
}
