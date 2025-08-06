// import { Toaster as Sonner } from '@/components/ui/sonner';
// import { Toaster } from '@/components/ui/toaster';
// import { TooltipProvider } from '@/components/ui/tooltip';
// import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
// import { BrowserRouter, Route, Routes } from 'react-router-dom';
// import ContentDetailsPage from './pages/ContentDetailsPage';
// import CreateReportPage from './pages/CreateReportPage';
// import EducationalContentPage from './pages/EducationalContentPage';
// import HomePage from './pages/HomePage';
// import Index from './pages/Index';
// import MapPage from './pages/MapPage';
// import NotFound from './pages/NotFound';
// import ReportDetailsPage from './pages/ReportDetailsPage';
// import ReportsPage from './pages/ReportsPage';

// const queryClient = new QueryClient();

// const App = () => (
//     <QueryClientProvider client={queryClient}>
//         <TooltipProvider>
//             <Toaster />
//             <Sonner />
//             <BrowserRouter>
//                 <Routes>
//                     <Route path="/" element={<Index />} />
//                     <Route path="/home" element={<HomePage />} />
//                     <Route path="/reports" element={<ReportsPage />} />
//                     <Route
//                         path="/create-report"
//                         element={<CreateReportPage />}
//                     />
//                     <Route
//                         path="/report-details/:id"
//                         element={<ReportDetailsPage />}
//                     />
//                     <Route
//                         path="/education"
//                         element={<EducationalContentPage />}
//                     />
//                     <Route
//                         path="/content-details/:id"
//                         element={<ContentDetailsPage />}
//                     />
//                     <Route path="/map" element={<MapPage />} />
//                     <Route path="*" element={<NotFound />} />
//                 </Routes>
//             </BrowserRouter>
//         </TooltipProvider>
//     </QueryClientProvider>
// );

// export default App;
