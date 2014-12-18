<?php 


namespace Mattias\FormCollection;
/**
*
* Collection of forms with the purpose of encapsulating creation. 
*
*
*/
class CFormCollection{

	// no members probably. 
    // Remove this method later
	public function __construct(){

	}

	public static function getCreateForm() {
		$form = new \Mos\HTMLForm\CForm();
		$form = $form->create(['class' => 'create-account-form'], [
            'acronym' => [
                'type'        => 'text',
                'label'       => 'Användarnamn',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'email' => [
                'type'        => 'text',
                'label'       => 'E-mail',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
            ],
            'name' => [
                'type' => 'text',
                'label' => 'Namn',
            ],
            'password' => [
                'type' => 'password', 
                'label' => 'Lösenord',
                'required' => true,
                'validation' => ['not_empty'],
            ],
            'password-repeat' => [
                'type' => 'password', 
                'label' => 'Upprepa lösenord',
                'required' => 'true', 
                'validation' => ['not_empty'],
            ],
            'submit' => [
                'type' => 'submit',
                'value' => 'Logga in',

            ]
        ]);
		return $form->getHTML();
	}

    public static function getLoginForm() {
        $form = new \Mos\HTMLForm\CForm();
        $form = $form->create(['class' => 'login-form'], [
            'acronym' => [
                'type'        => 'text',
                'label'       => 'Användarnamn',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'password' => [
                'type' => 'password', 
                'label' => 'Lösenord',
                'required' => true,
                'validation' => ['not_empty'],
            ],
            'submit' => [
                'type' => 'submit',
                'value' => 'Skapa konto',

            ]
            // // vad ska jag göra med nedan?? När det är in action liksom. 
            // 'submit' => [
            //     'type'      => 'submit',
            //     'callback'  => [$this, 'callbackSubmit'],
            // ],
            // 'submit-fail' => [
            //     'type'      => 'submit',
            //     'callback'  => [$this, 'callbackSubmitFail'],
            // ],
        ]);
        return $form->getHTML();
    }

    public static function getCreateQuestionForm() {
        $form = new \Mos\HTMLForm\CForm();
        $form = $form->create(['class' => 'create-question-form'], [
                'title' => [
                    'type' => 'text',
                    'label' => 'Titel',
                    'description' => '<span class="small">En beskrivande titel för din fråga som visas i översikter.</span>',
                    'required' => true,
                ],
                'text' => [
                    'type' => 'textarea', 
                    'label' => 'Fråga',
                    'description' => '<span class="small">Här skriver du din frågetext. Du kan använda Markdown för att formatera texten.</span>',
                    'required' => true,
                ],
                'tags' => [
                    'type' => 'text',
                    'label' => 'Taggar (separera med komma)',
                    'required' => true,
                ],
                'submit' => [
                    'type' => 'submit',
                    'value' => 'Spara fråga'
                ]
            ]);
        return $form->getHTML();
    }
    /**
    *
    * function to generate form for editing questions
    * @param $question to fill form values with existing question
    */
    public static function getEditQuestionForm($question) {
        $form = new \Mos\HTMLForm\CForm();
        $form = $form->create(['class' => 'edit-question-form'], [
                'title' => [
                    'type' => 'text',
                    'label' => 'Titel',
                    'required' => true,
                    'value' => $question->title
                ],
                'text' => [
                    'type' => 'textarea', 
                    'label' => 'Fråga',
                    'required' => true,
                    'value' => $question->text
                ],
                'tags' => [
                    'type' => 'text',
                    'label' => 'Taggar (separera med komma)',
                    'required' => true,
                    'value' => $question->tags
                ],
                'submit' => [
                    'type' => 'submit',
                    'value' => 'Ändra fråga'
                ]
            ]);
        return $form->getHTML();
    }

    public static function getCreateAnswerForm() {
        $form = new \Mos\HTMLForm\CForm();
        $form = $form->create(['class' => 'create-answer-form'], [
                'text' => [
                    'type' => 'textarea', 
                    'label' => 'Kommentar',
                    'required' => true,
                    'description' => '<span class="small">Här skriver du din kommentar. Du kan använda Markdown för att formatera texten. </span>'
                ],
                'submit' => [
                    'type' => 'submit',
                    'value' => 'Spara svar'
                ]
            ]);
        return $form->getHTML();
    }

    public function getEditAccountForm($profile) {
        $form = new \Mos\HTMLForm\CForm();

        $form = $form->create(['class' => 'edit-profile-form'], [
                'email' => [
                    'type' => 'text',
                    'label' => 'E-mail',
                    'value' => $profile->email
                ],
                'name' => [
                    'type' => 'text', 
                    'label' => 'Namn',
                    'value' => $profile->name
                ],
                'text' => [
                    'type' => 'textarea', 
                    'label' => 'Presentationstext',
                    'value' => $profile->text,
                ],
                'password' => [
                    'type' => 'password',
                    'label' => 'Lösenord',
                    'value' => '',
                    'required' => true
                ],
                'password-repeat' => [
                    'type' => 'password',
                    'label' => 'Upprepa lösenord',
                    'value' => '',
                    'required' => true
                ],
                'submit' => [
                    'type' => 'submit',
                    'value' => 'Spara profil'
                ]
            ]);
        return $form->getHTML();
    }

    public function getCreateCommentForm($id) {
        $form = new \Mos\HTMLForm\CForm();

        $form = $form->create(['class' => 'create-comment-form'], [
                'iscommentto' => [
                    'type' => 'hidden',
                    'value' => $id
                ],
                'text' => [
                    'type' => 'textarea',
                    'label' => 'Kommentera'
                ],
                'submitcomment' => [
                    'type' => 'submit',
                    'value' => 'Spara kommentar'
                ]
            ]);
        return $form->getHTML();
    }
}