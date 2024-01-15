<?php

namespace Tests\Feature\Controller\Api;

use App\Models\Submitter;
use App\Models\User;
use App\Services\GuestbookEntryService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\GuestbookEntry;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GuestbookControllerTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    /**
     * @return void
     */
    public function testCheckGuestbookIndexEndpoint(): void
    {
        $data = [
            'title'        => 'This is really amazing',
            'content'      => 'Much better than Amazon',
        ];

        GuestbookEntry::factory()->create($data);

        $response = $this->get(route('guestbook.index'));

        // Assert if this is status 200
        $response->assertOk();

        $content = json_decode($response->getContent());

        // assert if data is there
        $this->assertCount(1, $content);

        $entry = reset($content);

        $this->assertEquals($entry->title, $data['title']);
        $this->assertEquals($entry->content, $data['content']);

        // Assert submitter
        $submitter = Submitter::where('id', '=', $entry->submitter_id)->get();
        $this->assertCount(1, $submitter);

        $this->assertEquals($entry->submitter_id, $submitter[0]->id);
    }

    /**
     * @return void
     */
    public function testMyEndpointFail(): void
    {
        $response = $this->get(route('guestbook.my'));

        // Assert if this is status 401
        $response->assertStatus(401);

    }

    /**
     * @return void
     */
    public function testMyEndpointSuccess(): void
    {
        /** @var GuestbookEntryService $service */
        $service = app(GuestbookEntryService::class);

        $data = [
            'title'        => $this->faker->word,
            'content'      => $this->faker->sentence,
            'email'        => $this->faker->safeEmail,
            'display_name' => $this->faker->name,
            'real_name'    => $this->faker->name,
        ];

        $createdEntry = $service->createGuestbookEntry($data);

        $user = User::factory()->create(['email' => $createdEntry->submitter->email]);

        //Login the user
        Auth::login($user);

        $response = $this->get(route('guestbook.my'));

        // Assert if this is status 200
        $response->assertOk();

        // assert if data is there
        $content = json_decode($response->getContent());

        $this->assertCount(1, $content);

        $resultEntry = reset($content);

        $this->assertEquals($createdEntry->title, $resultEntry->title);
        $this->assertEquals($createdEntry->submitter_id, $resultEntry->submitter_id);
    }

    /**
     * @return void
     */
    public function testCheckGuestbookDeleteEndpoint(): void
    {
        /** @var GuestbookEntryService $service */
        $service = app(GuestbookEntryService::class);

        $data = [
            'title'        => $this->faker->word,
            'content'      => $this->faker->sentence,
            'email'        => $this->faker->safeEmail,
            'display_name' => $this->faker->name,
            'real_name'    => $this->faker->name,
        ];

        $createdEntry = $service->createGuestbookEntry($data);

        $this->delete(route('guestbook.delete', ['entry' => $createdEntry->id]));

        // Assert entry
        $entry = GuestbookEntry::where('id', '=', $createdEntry->id)->get();
        $this->assertCount(0, $entry);
    }
}
