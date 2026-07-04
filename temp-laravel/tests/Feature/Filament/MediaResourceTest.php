<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\MediaResource\Pages\CreateMedia;
use App\Models\Media;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class MediaResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_media_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('banner.jpg');

        Livewire::test(CreateMedia::class)
            ->fillForm([
                'upload' => $file,
                'alt' => 'Banner alt',
                'caption' => 'Banner caption',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseCount('media', 1);

        $media = Media::query()->firstOrFail();

        $this->assertSame('banner', $media->original_name);
        $this->assertSame('banner.jpg', $media->filename);
        $this->assertSame('Banner alt', $media->alt);
        $this->assertSame($user->id, $media->created_by);
    }

    public function test_media_resource_supports_search_filter_and_sort_labels(): void
    {
        $media = new Media([
            'mime_type' => 'image/jpeg',
            'disk' => 'public',
            'path' => 'images/2026/06/banner.jpg',
        ]);

        $this->assertSame('Image', $media->type_label);
        $this->assertTrue($media->is_image);
    }

    public function test_image_preview_source_is_rendered_as_data_url_when_file_exists(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('images/2026/06/banner.jpg', 'binary-image-content');

        $media = new Media([
            'mime_type' => 'image/jpeg',
            'disk' => 'public',
            'path' => 'images/2026/06/banner.jpg',
        ]);

        $source = app(MediaService::class)->previewImageSource($media);

        $this->assertStringStartsWith('data:image/jpeg;base64,', $source);
    }

    public function test_pdf_preview_source_is_rendered_as_data_url_when_file_exists(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('pdf/2026/06/manual.pdf', '%PDF-1.4 binary-pdf-content');

        $media = new Media([
            'mime_type' => 'application/pdf',
            'disk' => 'public',
            'path' => 'pdf/2026/06/manual.pdf',
        ]);

        $source = app(MediaService::class)->previewPdfSource($media);

        $this->assertStringStartsWith('data:application/pdf;base64,', $source);
    }

    public function test_uploaded_image_is_stored_in_images_directory(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('banner.jpg');
        $storedPath = $file->storeAs('uploads/tmp/2026/06', 'banner.jpg', 'public');

        $media = app(MediaService::class)->createFromStoredUpload($storedPath, [
            'original_name' => 'banner',
            'created_by' => $user->id,
        ]);

        $this->assertStringStartsWith('images/', $media->path);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_uploaded_pdf_is_stored_in_pdf_directory(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('manual.pdf', 200, 'application/pdf');
        $storedPath = $file->storeAs('uploads/tmp/2026/06', 'manual.pdf', 'public');

        $media = app(MediaService::class)->createFromStoredUpload($storedPath, [
            'original_name' => 'manual',
            'created_by' => $user->id,
        ]);

        $this->assertStringStartsWith('pdf/', $media->path);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_uploaded_video_is_stored_in_videos_directory(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('intro.mp4', 2000, 'video/mp4');
        $storedPath = $file->storeAs('uploads/tmp/2026/06', 'intro.mp4', 'public');

        $media = app(MediaService::class)->createFromStoredUpload($storedPath, [
            'original_name' => 'intro',
            'created_by' => $user->id,
        ]);

        $this->assertStringStartsWith('videos/', $media->path);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_upload_rejects_invalid_mime_type(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.txt', 10, 'text/plain');
        $storedPath = $file->storeAs('uploads/tmp/2026/06', 'notes.txt', 'public');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Unsupported file type');

        app(MediaService::class)->createFromStoredUpload($storedPath, [
            'original_name' => 'notes',
        ]);
    }

    public function test_upload_rejects_invalid_extension_for_detected_file_type(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('invoice.pdf');
        $storedPath = $file->storeAs('uploads/tmp/2026/06', 'invoice.pdf', 'public');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid file extension');

        app(MediaService::class)->createFromStoredUpload($storedPath, [
            'original_name' => 'invoice',
        ]);
    }

    public function test_upload_rejects_file_size_over_limit_for_pdf(): void
    {
        Storage::fake('public');

        $storedPath = 'uploads/tmp/2026/06/huge.pdf';
        Storage::disk('public')->put($storedPath, str_repeat('A', 51 * 1024 * 1024));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('File size exceeds');

        app(MediaService::class)->createFromStoredUpload($storedPath, [
            'original_name' => 'huge',
        ]);
    }
}
