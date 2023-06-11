<?php

return [
    'teams' => 'Team|Teams',
    'personal_team_name' => 'Personal Team',
    'create_team' => 'Create team',
    'current' => 'Current',
    'team_name' => 'Team name',

    'create_team_form' => [
        'modal_title' => 'Create a new team',
        'modal_text' => 'Use this form to create a new team. You can add team members to a team after creating it.',
        'button' => 'Create team',
    ],

    'settings_page' => [
        'meta_title' => ':team_name settings',
        'page_title' => ':team_name settings',
    ],

    'general_section' => [
        'title' => 'General settings',
        'description' => 'Update your team\'s general settings.',
        'form_button' => 'Update team',
    ],

    'members_section' => [
        'title' => 'Members',
        'description' => 'Manage the members of your team.',
        'invite_button' => 'Add member',
        'invite_modal_title' => 'Invite a new team member',
        'invite_modal_text' => 'Enter the email address of the person you would like to invite to this team. If they do not have an account, they will be prompted to create one.',
        'invite_modal_button' => 'Send invitation',
        'personal_team_text' => 'You cannot invite members to your personal team.',
        'search_placeholder' => 'Find a team member...',
        'remove_member' => 'Remove member',
        'resend_invitation' => 'Resend invitation',
        'delete_invitation' => 'Delete invitation',
    ],

    'delete_section' => [
        'title' => 'Delete team',
        'description' => 'Permanently delete this team and all of its resources.',
        'personal_team_text' => 'You cannot delete your personal team.',
        'form_button' => 'Delete team',
        'modal_title' => 'Delete team',
        'modal_text' => 'Are you sure you want to delete this team? Once a team is deleted, all of its resources and data will be permanently deleted, including its short links and members.',
    ],

    'team_update_success' => [
        'title' => 'Team updated',
        'message' => ':team_name team has been updated successfully.',
    ],

    'invitation_sent_success' => [
        'title' => 'Invitation sent',
        'message' => 'An invitation has been sent to :email.',
    ],

    'team_delete_success' => [
        'title' => 'Team deleted',
        'message' => ':team_name team has been deleted successfully.',
    ],

    'team_delete_error' => [
        'title' => 'Team not deleted',
        'message' => 'The :team_name team could not be deleted. Personal teams cannot be deleted.',
    ],
];
