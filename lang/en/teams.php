<?php

return [
    'teams' => 'Team|Teams',
    'team_settings' => 'Team settings',
    'personal_team_name' => 'Personal Team',
    'create_team' => 'Create team',
    'current' => 'Current',
    'create_team_modal_title' => 'Create a new team',
    'create_team_modal_text' => 'Use this form to create a new team. You can add team members to a team after creating it.',
    'create_team_button' => 'Create team',
    'team_name' => 'Team name',
    'team_name_taken' => 'You already have a team called :team_name.',

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
        'add_member' => 'Add member',
        'personal_team_text' => 'You cannot add members to your personal team, create a new team to add members.',
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

    'team_delete_success' => [
        'title' => 'Team deleted',
        'message' => ':team_name team has been deleted successfully.',
    ],

    'team_delete_error' => [
        'title' => 'Team not deleted',
        'message' => 'The :team_name team could not be deleted. Personal teams cannot be deleted.',
    ],
];
