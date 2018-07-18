<?php

namespace Logistio\Symmetry\Process\Updater;

interface ProcessUpdaterInterface
{
    public function update(ProcessUpdaterPayloadInterface $payload);
}