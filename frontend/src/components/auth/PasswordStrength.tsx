const STRENGTH_LEVELS = [
  { label: "Too short", color: "bg-gray-200" },
  { label: "Weak", color: "bg-red-400" },
  { label: "Fair", color: "bg-yellow-400" },
  { label: "Good", color: "bg-blue-400" },
  { label: "Strong", color: "bg-green-500" },
] as const;

const calcStrength = (pw: string) => {
  let score = 0;
  if (pw.length >= 8) score++;
  if (/[A-Z]/.test(pw)) score++;
  if (/[0-9]/.test(pw)) score++;
  if (/[^A-Za-z0-9]/.test(pw)) score++;
  return { score, ...STRENGTH_LEVELS[score] };
};
const PasswordStrength = ({ password }: { password: string }) => {
  if (!password) return null;
  const { score, label, color } = calcStrength(password);
  return (
    <div className="mt-1 mb-1">
      <div className="flex gap-1 mb-1">
        {[...Array(4)].map((_, i) => (
          <div
            key={i}
            className={`h-1 flex-1 rounded-full transition-all duration-300 ${
              i < score ? color : "bg-gray-100"
            }`}
          />
        ))}
      </div>
      <p className="text-xs text-gray-400">{label}</p>
    </div>
  );
};

export default PasswordStrength;
