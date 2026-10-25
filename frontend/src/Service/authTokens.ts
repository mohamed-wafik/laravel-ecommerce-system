import cookieService from "./cookie.service";

export const ACCESS_TOKEN = "access_token";
export const REFRESH_TOKEN = "refresh_token";

const ACCESS_TOKEN_MAX_AGE = 60 * 30; // 30 minutes
const REFRESH_TOKEN_MAX_AGE = 60 * 60 * 24 * 30; // 30 days

const cookieOptions = {
  path: "/",
  sameSite: "lax" as const,
  secure: import.meta.env.PROD,
};

export const getAccessToken = () => cookieService.get(ACCESS_TOKEN);

export const getRefreshToken = () => cookieService.get(REFRESH_TOKEN);

export const setTokens = (accessToken: string, refreshToken: string) => {
  cookieService.set(ACCESS_TOKEN, accessToken, {
    ...cookieOptions,
    maxAge: ACCESS_TOKEN_MAX_AGE,
  });

  cookieService.set(REFRESH_TOKEN, refreshToken, {
    ...cookieOptions,
    maxAge: REFRESH_TOKEN_MAX_AGE,
  });
};

export const clearTokens = () => {
  cookieService.remove(ACCESS_TOKEN);

  cookieService.remove(REFRESH_TOKEN);
};
