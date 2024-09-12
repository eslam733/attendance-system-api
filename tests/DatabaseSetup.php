<?php

namespace Tests;

use App\Models\Journey;
use App\Models\JourneyProgram;
use App\Models\File;
use App\Models\Program;
use App\Models\ProgramChapter;
use App\Models\ProgramChapterItem;
use App\Models\Section;
use App\Models\SubChapter;
use App\Models\TextLesson;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

trait DatabaseSetup
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }
}
