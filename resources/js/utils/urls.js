/**
 * Laravel's paginator can return absolute URLs based on APP_URL.
 * If APP_URL is misconfigured in production (e.g. localhost), Inertia <Link>
 * will attempt a cross-origin request and fail (Axios ERR_NETWORK).
 *
 * This helper normalizes paginator URLs to same-origin relative URLs.
 */
export function normalizeSameOriginUrl(url) {
    if (!url) return url;

    try {
        // Handles both absolute and relative inputs.
        const u = new URL(url, window.location.origin);

        // Always return a relative URL (path + query + hash) to avoid origin issues.
        return `${u.pathname}${u.search}${u.hash}`;
    } catch {
        // If URL parsing fails, fall back to the original.
        return url;
    }
}

