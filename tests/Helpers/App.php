<?php

function disableRegistration(): void
{
    config()->set('blast.disable_registration', true);
}
