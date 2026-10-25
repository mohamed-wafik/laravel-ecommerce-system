import { useRef, useState } from "react";
import { Camera, Trash2, Loader2, User } from "lucide-react";
import toast from "react-hot-toast";
import { getErrorMessage } from "../../lib/utils";
import { useAuthStore } from "../../store/useAuth";
import { profileAPI } from "../../api/modules/Customer/profile.api";

const MAX_SIZE_MB = 2.5;

const AvatarUpload = () => {
  const { user, check } = useAuthStore();
  const fileRef = useRef<HTMLInputElement>(null);
  const [uploading, setUploading] = useState(false);
  const [removing, setRemoving] = useState(false);

  const handleFileChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    // Client-side size guard
    if (file.size > MAX_SIZE_MB * 1024 * 1024) {
      toast.error(`Image must be under ${MAX_SIZE_MB} MB`);
      return;
    }

    const form = new FormData();
    form.append("avatar", file);

    setUploading(true);
    try {
      await profileAPI.updateProfile(form);
      await check(); // refresh user in store
      toast.success("Avatar updated ✅");
    } catch (err) {
      toast.error(getErrorMessage(err));
    } finally {
      setUploading(false);
      // Reset input so the same file can be re-selected
      if (fileRef.current) fileRef.current.value = "";
    }
  };

  const handleRemove = async () => {
    setRemoving(true);
    try {
      await profileAPI.removeAvatar();

      useAuthStore.setState({
        user: user?.avatar_url ? { ...user, avatar_url: null } : user,
      });
      toast.success("Avatar removed");
    } catch (err) {
      toast.error(getErrorMessage(err));
    } finally {
      setRemoving(false);
    }
  };

  const busy = uploading || removing;

  return (
    <div className="flex flex-col items-center gap-3">
      {/* Avatar circle */}
      <div className="relative group">
        <div
          className={`w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-md bg-gray-100 flex items-center justify-center transition-opacity ${
            busy ? "opacity-60" : ""
          }`}
        >
          {user?.avatar_url ? (
            <img
              src={user.avatar_url}
              alt={user.name}
              className="w-full h-full object-cover"
            />
          ) : (
            <User className="w-10 h-10 text-gray-300" />
          )}
        </div>

        {/* Upload overlay */}
        {!busy && (
          <button
            type="button"
            onClick={() => fileRef.current?.click()}
            className="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
            aria-label="Change avatar"
          >
            <Camera className="w-6 h-6 text-white" />
          </button>
        )}

        {/* Loading spinner overlay */}
        {busy && (
          <div className="absolute inset-0 rounded-full bg-black/30 flex items-center justify-center">
            <Loader2 className="w-6 h-6 text-white animate-spin" />
          </div>
        )}
      </div>

      {/* Actions */}
      <div className="flex items-center gap-2">
        <button
          type="button"
          onClick={() => fileRef.current?.click()}
          disabled={busy}
          className="text-xs font-semibold text-primary hover:underline disabled:opacity-40 transition-opacity"
        >
          {uploading ? "Uploading…" : "Change photo"}
        </button>

        {user?.avatar_url && (
          <>
            <span className="text-gray-300 text-xs">·</span>
            <button
              type="button"
              onClick={handleRemove}
              disabled={busy}
              className="text-xs font-semibold text-red-400 hover:text-red-600 flex items-center gap-1 disabled:opacity-40 transition-colors"
            >
              {removing ? (
                <Loader2 className="w-3 h-3 animate-spin" />
              ) : (
                <Trash2 className="w-3 h-3" />
              )}
              {removing ? "Removing…" : "Remove"}
            </button>
          </>
        )}
      </div>

      <p className="text-xs text-gray-400">
        JPG, PNG or WebP · max {MAX_SIZE_MB} MB
      </p>

      {/* Hidden file input */}
      <input
        ref={fileRef}
        type="file"
        accept="image/jpg,image/jpeg,image/png,image/webp"
        className="hidden"
        onChange={handleFileChange}
      />
    </div>
  );
};

export default AvatarUpload;
