import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Head } from '@inertiajs/react';
import { PageProps } from '@/types';
import { User, Mail, Phone, Shield } from 'lucide-react';

export default function Profile({ auth }: PageProps) {
    return (
        <AdminLayout>
            <Head title="Profile" />
            
            <div className="space-y-6">
                <div>
                    <h1 className="text-3xl font-bold text-slate-50">Profile</h1>
                    <p className="text-slate-400 mt-1">Your account information</p>
                </div>

                <div className="grid gap-6 md:grid-cols-2">
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Personal Information</CardTitle>
                            <CardDescription className="text-slate-400">Your profile details</CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="flex items-center space-x-3">
                                <User className="h-5 w-5 text-slate-500" />
                                <div>
                                    <p className="text-sm text-slate-400">Name</p>
                                    <p className="text-slate-50 font-medium">{auth.user.name}</p>
                                </div>
                            </div>
                            <div className="flex items-center space-x-3">
                                <Mail className="h-5 w-5 text-slate-500" />
                                <div>
                                    <p className="text-sm text-slate-400">Email</p>
                                    <p className="text-slate-50 font-medium">{auth.user.email}</p>
                                </div>
                            </div>
                            {auth.user.phone && (
                                <div className="flex items-center space-x-3">
                                    <Phone className="h-5 w-5 text-slate-500" />
                                    <div>
                                        <p className="text-sm text-slate-400">Phone</p>
                                        <p className="text-slate-50 font-medium">{auth.user.phone}</p>
                                    </div>
                                </div>
                            )}
                            <div className="flex items-center space-x-3">
                                <Shield className="h-5 w-5 text-slate-500" />
                                <div>
                                    <p className="text-sm text-slate-400">Role</p>
                                    <Badge className="bg-blue-900/50 text-blue-200 border-blue-700">
                                        Administrator
                                    </Badge>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Account Status</CardTitle>
                            <CardDescription className="text-slate-400">Current account state</CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div>
                                <p className="text-sm text-slate-400">Status</p>
                                <Badge className="bg-emerald-900/50 text-emerald-200 border-emerald-700">
                                    {auth.user.status || 'Active'}
                                </Badge>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Member Since</p>
                                <p className="text-slate-50">{new Date(auth.user.created_at).toLocaleDateString()}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AdminLayout>
    );
}


