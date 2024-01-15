<?php

namespace Tests\Feature\Controller\Web;

use App\Models\Submitter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\GuestbookEntry;
use Tests\TestCase;

class WebControllerTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    /**
     * @return void
     */
    public function testCheckIndexEndpoint(): void
    {
        $data = [
            'title'        => 'This is really amazing',
            'content'      => 'Much better than Amazon',
        ];

        GuestbookEntry::factory()->create($data);

        $response = $this->get(route('index'));

        // Assert if this is status 200
        $response->assertOk();

        // assert if data is there
        $response->assertSeeText($data['title']);
        $response->assertSeeText($data['content']);

        // assert view is correct
        $response->assertViewIs('index');
    }

    /**
     * @return void
     */
    public function testCheckFormEndpoint(): void
    {
        $response = $this->get(route('submit'));

        // Assert if this is status 200
        $response->assertOk();

        // assert view is correct
        $response->assertViewIs('form');
    }

    /**
     * @return void
     */
    public function testCheckCreateEndpointFail(): void
    {
        $response = $this->post(route('create'));

        // Assert if this is status 302
        $response->assertRedirect();

        $response->assertSessionHasErrors();
    }

    /**
     * @return void
     */
    public function testCheckCreateEndpointSuccess(): void
    {
        $data = [
            'title'        => $this->faker->word,
            'content'      => $this->faker->sentence,
            'email'        => $this->faker->safeEmail,
            'display_name' => $this->faker->name,
            'real_name'    => $this->faker->name,
        ];
        $response = $this->post(route('create'), $data);

        // Assert if this is status 200
        $response->assertOk();

        // Assert if returns the form
        $response->assertViewIs('form');

        // Assert data has inserted
        $submitter = Submitter::where('email', '=', $data['email'])->get();
        $this->assertCount(1, $submitter);

        $entry = GuestbookEntry::where('submitter_id', '=', $submitter[0]->id)->get();
        $this->assertCount(1, $entry);
    }

}
