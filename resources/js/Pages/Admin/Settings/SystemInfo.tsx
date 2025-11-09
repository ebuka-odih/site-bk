import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import { PageProps } from '@/types';

export default function SystemInfo({ info }: PageProps & { info: any }) {
    return (
        <AdminLayout>
            <Head title="System Information" />
            
            <div className="space-y-6">
                <div>
                    <Link href="/admin/settings" className="inline-flex items-center text-sm text-slate-400 hover:text-slate-50 mb-4">
                        <ArrowLeft className="h-4 w-4 mr-1" />
                        Back to Settings
                    </Link>
                    <h1 className="text-3xl font-bold text-slate-50">System Information</h1>
                    <p className="text-slate-400 mt-1">Current system status and configuration</p>
                </div>

                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50">Server Information</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p className="text-sm text-slate-400">PHP Version</p>
                                <p className="text-slate-50 font-medium">{info.php_version}</p>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Laravel Version</p>
                                <p className="text-slate-50 font-medium">{info.laravel_version}</p>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Environment</p>
                                <p className="text-slate-50 font-medium">{info.environment}</p>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Debug Mode</p>
                                <p className="text-slate-50 font-medium">{info.debug_mode ? 'Enabled' : 'Disabled'}</p>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Database</p>
                                <p className="text-slate-50 font-medium">{info.database_connection}</p>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Cache Driver</p>
                                <p className="text-slate-50 font-medium">{info.cache_driver}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {info.disk_space && (
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Disk Space</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-4">
                                <div className="flex justify-between">
                                    <span className="text-slate-400">Total</span>
                                    <span className="text-slate-50 font-medium">{info.disk_space.total}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-slate-400">Used</span>
                                    <span className="text-slate-50 font-medium">{info.disk_space.used}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-slate-400">Free</span>
                                    <span className="text-slate-50 font-medium">{info.disk_space.free}</span>
                                </div>
                                <div className="w-full bg-slate-800 rounded-full h-2">
                                    <div
                                        className="bg-blue-500 h-2 rounded-full"
                                        style={{ width: `${info.disk_space.percentage}%` }}
                                    />
                                </div>
                                <p className="text-xs text-slate-500 text-center">{info.disk_space.percentage}% used</p>
                            </div>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AdminLayout>
    );
}


