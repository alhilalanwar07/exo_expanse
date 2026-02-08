<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvitationService
{
    /**
     * Create a new invitation.
     */
    public function create(User $user, array $data): Invitation
    {
        $data['user_id'] = $user->id;
        $data['slug'] = $this->generateUniqueSlug($data['title'] ?? 'undangan');

        return Invitation::create($data);
    }

    /**
     * Update an existing invitation.
     */
    public function update(Invitation $invitation, array $data): Invitation
    {
        // Regenerate slug if title changed
        if (isset($data['title']) && $data['title'] !== $invitation->title) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $invitation->id);
        }

        $invitation->update($data);

        return $invitation->refresh();
    }

    /**
     * Delete an invitation (soft delete).
     */
    public function delete(Invitation $invitation): bool
    {
        return $invitation->delete();
    }

    /**
     * Force delete an invitation permanently.
     */
    public function forceDelete(Invitation $invitation): bool
    {
        // Delete gallery images from storage
        if ($invitation->gallery_images) {
            foreach ($invitation->gallery_images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        // Delete cover image from storage
        if ($invitation->cover_image) {
            Storage::disk('public')->delete($invitation->cover_image);
        }

        return $invitation->forceDelete();
    }

    /**
     * Generate a unique slug.
     */
    public function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        $query = Invitation::withTrashed()->where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;

            $query = Invitation::withTrashed()->where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Publish an invitation.
     */
    public function publish(Invitation $invitation): Invitation
    {
        $invitation->publish();

        return $invitation;
    }

    /**
     * Unpublish an invitation.
     */
    public function unpublish(Invitation $invitation): Invitation
    {
        $invitation->unpublish();

        return $invitation;
    }

    /**
     * Duplicate an invitation.
     */
    public function duplicate(Invitation $invitation, User $user): Invitation
    {
        $data = $invitation->toArray();

        unset($data['id'], $data['slug'], $data['created_at'], $data['updated_at'], $data['deleted_at']);

        $data['title'] = $invitation->title.' (Copy)';
        $data['is_published'] = false;

        return $this->create($user, $data);
    }
}
