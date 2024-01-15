<?php

namespace Tests\Feature\Services;

use App\Models\GuestbookEntry;
use App\Models\Submitter;
use App\Services\GuestbookEntryService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuestbookEntryServiceTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    /**
     * @var GuestbookEntryService $service
     */
    private GuestbookEntryService $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = app(GuestbookEntryService::class);
    }

    public function testCreateGuestBookEntry(): void
    {
        $data = [
            'title'        => $this->faker->word,
            'content'      => $this->faker->sentence,
            'email'        => $this->faker->safeEmail,
            'display_name' => $this->faker->name,
            'real_name'    => $this->faker->name,
        ];

        $result = $this->service->createGuestbookEntry($data);

        // Assert data is correct
        $this->assertEquals($data['title'], $result->title);
        $this->assertEquals($data['content'], $result->content);

        $this->assertEquals($data['email'], $result->submitter->email);
        $this->assertEquals($data['display_name'], $result->submitter->display_name);
        $this->assertEquals($data['real_name'], $result->submitter->real_name);

        // Assert only one submitter
        $submitter = Submitter::where('email', '=', $data['email'])->get();
        $this->assertCount(1, $submitter);
    }

    public function testUpdateGuestBookEntry(): void
    {
        $data = [
            'title'        => $this->faker->word,
            'content'      => $this->faker->sentence,
            'email'        => $this->faker->safeEmail,
            'display_name' => $this->faker->name,
            'real_name'    => $this->faker->name,
        ];

        $entry = $this->service->createGuestbookEntry($data);

        $updateData = [
            'title'        => 'Title update',
            'content'      => 'Content update',
        ];

        $entry = $this->service->update($entry, $updateData);

        // Assert data is correct
        $this->assertEquals($updateData['title'], $entry->title);
        $this->assertEquals($updateData['content'], $entry->content);

        // Get the DB value, to check if its the same has the sent data
        $entryDB = GuestbookEntry::where('submitter_id', '=', $entry->submitter_id)->get();

        // Assert data is correct
        $this->assertEquals($updateData['title'], $entryDB[0]->title);
        $this->assertEquals($updateData['content'], $entryDB[0]->content);

        // Assert only one submitter and exists
        $submitter = Submitter::where('email', '=', $data['email'])->get();
        $this->assertCount(1, $submitter);
    }

    public function testGetUserEntriesByEmail(): void
    {
        $email = $this->faker->safeEmail;

        $firstEntry = [
            'title'        => $this->faker->word,
            'content'      => $this->faker->sentence,
            'email'        => $email,
            'display_name' => $this->faker->name,
            'real_name'    => $this->faker->name,
        ];

        $secondEntry = [
            'title'        => $this->faker->word,
            'content'      => $this->faker->sentence,
            'email'        => $email,
            'display_name' => $this->faker->name,
            'real_name'    => $this->faker->name,
        ];

        $this->service->createGuestbookEntry($firstEntry);
        $this->service->createGuestbookEntry($secondEntry);

        $entries = $this->service->getUserEntriesByEmail($email);

        // Assert that there are 2 entries
        $this->assertCount(2, $entries);
    }

    public function testDeleteGuestbookEntry(): void
    {
        $data = [
            'title'        => $this->faker->word,
            'content'      => $this->faker->sentence,
            'email'        => $this->faker->safeEmail,
            'display_name' => $this->faker->name,
            'real_name'    => $this->faker->name,
        ];

        $createdEntry = $this->service->createGuestbookEntry($data);

        $this->service->delete($createdEntry);

        // Get the DB value, to check if it is deleted
        $entryDB = GuestbookEntry::where('id', '=', $createdEntry->id)->get();

        $this->assertCount(0, $entryDB);
    }

}
