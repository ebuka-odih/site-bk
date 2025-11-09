declare module 'ziggy-js' {
    interface RouteParams {
        [key: string]: any;
    }

    interface Router {
        (name: string, params?: RouteParams, absolute?: boolean): string;
        current(name?: string, params?: RouteParams): boolean | string;
    }

    const route: Router;
    export { route };
}


