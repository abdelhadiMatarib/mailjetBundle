<?php

namespace Welp\MailjetBundle\Model;

/**
* https://dev.mailjet.com/email-api/v3/contactslist-managemanycontacts/
* Object to manage many contacts from list
*/
class ContactsList
{

    const ACTION_ADDFORCE = 'addforce'; # adds the contact and resets the unsub status to false
    const ACTION_ADDNOFORCE = 'addnoforce'; # adds the contact and does not change the subscription status of the contact
    const ACTION_REMOVE = 'remove'; # removes the contact from the list
    const ACTION_UNSUB = 'unsub'; # unsubscribes a contact from the list

    protected $listId;
    protected $action;
    protected $contacts;


    /**
     * @param Int $listId
     * @param String $action see const ACTION_*
     * @param Array of Contact
     */
    public function __construct($listId, $action, $contacts)
    {
        if(!$this->validateAction($action)){
            throw new \RuntimeException("$action: is not a valide Action.");
        }

        $this->listId = $listId;
        $this->action = $action;
        $this->contacts = $contacts;
    }

    /**
     * Formate contactList for MailJet API request
     * @return array
     */
    public function format(){

        $result = [
            'Action' => $this->action,
            'Contacts' => [],
        ];

        $contacts = $this->contacts;
        $contacsArray = array_map(function(Contact $contact) {
            return $contact->format();
        }, $contacts);

        $result['Contacts'] = $contacsArray;

        return $result;
    }


    /**
     *
     */
    public function getListId()
    {
        return $this->listId;
    }

    /**
     *
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     *
     */
    public function getContacts()
    {
        return $this->contacts;
    }



    /**
     * Validate if action is authorized
     * @return Boolean
     */
    private function validateAction($action)
    {
        $actionAvailable = [self::ACTION_ADDFORCE, self::ACTION_ADDNOFORCE, self::ACTION_REMOVE, self::ACTION_UNSUB];
        if(in_array($action, $actionAvailable)){
            return true;
        }
        return false;
    }


}
