# Musea

## Technical Architecture: Multi-Port Development

To manage the **Admin Panel** separately from the **Main Store**, we utilize a multi-port development environment. This allows us to keep the user-facing storefront and the administrative dashboard isolated, facilitating easier testing of API permissions and UI changes simultaneously.

- **Port 8000**: Main Storefront (User Facing)
- **Port 8001**: Admin Interface (Admin Panel)

### Why separate ports?

- **Port Segregation**: Running different parts of the same app on different local ports to simulate distinct access points.
- **Parallel Instances**: Running two versions of the Laravel server at the same time.
- **Cross-Origin Considerations**: If the Admin frontend on Port 8001 needs to talk to the Main Store API on Port 8000, CORS (Cross-Origin Resource Sharing) must be configured.

### Visualizing the Setup

Your local architecture looks like this:
- Terminal 1: `php artisan serve` (Port 8000)
- Terminal 2: `php artisan serve --port=8001` (Port 8001)

### Quick Setup

We have configured convenience scripts in `package.json` to make this easier:

```bash
# To run the Main App
npm run serve

# To run the Admin Panel (in a new terminal tab)
npm run serve:admin
```

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- [Multiple back-ends for session and cache storage](https://laravel.com/docs/session).
- [Expressive, intuitive database ORM](https://laravel.com/docs/eloquent).
- [Database agnostic schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
