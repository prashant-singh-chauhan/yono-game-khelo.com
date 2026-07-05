<?php

use App\Models\Query;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('stores a contact message with an uploaded attachment', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('proof.png', 400, 300);

    $response = $this->post('/contact', [
        'sender_name' => 'Attachment Tester',
        'email'       => 'attach@test.com',
        'subject'     => 'Here is a file',
        'message'     => 'Please find the attachment.',
        'attachment'  => $file,
    ]);

    $response->assertRedirect();

    $query = Query::where('email', 'attach@test.com')->first();

    expect($query)->not->toBeNull();
    expect($query->attachment)->not->toBeNull();
    expect($query->attachmentUrl())->toContain('/storage/attachments/');
    Storage::disk('public')->assertExists($query->attachment);
});

it('accepts a contact message without an attachment', function () {
    $response = $this->post('/contact', [
        'sender_name' => 'No File',
        'email'       => 'nofile@test.com',
        'message'     => 'Just a message.',
    ]);

    $response->assertRedirect();
    expect(Query::where('email', 'nofile@test.com')->first()?->attachment)->toBeNull();
});

it('rejects an oversized or wrong-type attachment', function () {
    $bad = UploadedFile::fake()->create('malware.exe', 100, 'application/x-msdownload');

    $response = $this->from('/contact')->post('/contact', [
        'sender_name' => 'Bad File',
        'email'       => 'bad@test.com',
        'message'     => 'Trying a bad file.',
        'attachment'  => $bad,
    ]);

    $response->assertSessionHasErrors('attachment');
    expect(Query::where('email', 'bad@test.com')->exists())->toBeFalse();
});
