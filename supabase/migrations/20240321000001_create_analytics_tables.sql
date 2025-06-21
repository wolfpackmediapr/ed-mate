-- Create course_pricing table
CREATE TABLE IF NOT EXISTS course_pricing (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    course_id UUID NOT NULL REFERENCES courses(id) ON DELETE CASCADE,
    user_id UUID NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
    price DECIMAL(10,2) NOT NULL,
    is_paid BOOLEAN DEFAULT FALSE,
    count INTEGER DEFAULT 1,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL
);

-- Create payments table
CREATE TABLE IF NOT EXISTS payments (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    user_id UUID NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
    course_id UUID NOT NULL REFERENCES courses(id) ON DELETE CASCADE,
    amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    payment_method VARCHAR(50),
    transaction_id VARCHAR(100),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL
);

-- Create course_progress table
CREATE TABLE IF NOT EXISTS course_progress (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    user_id UUID NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
    course_id UUID NOT NULL REFERENCES courses(id) ON DELETE CASCADE,
    completed_lessons INTEGER DEFAULT 0,
    total_lessons INTEGER NOT NULL,
    last_accessed_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
    UNIQUE(user_id, course_id)
);

-- Create user_activity table
CREATE TABLE IF NOT EXISTS user_activity (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    user_id UUID NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
    last_active TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
    activity_type VARCHAR(50),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL
);

-- Create indexes for better query performance
CREATE INDEX IF NOT EXISTS idx_course_pricing_course_id ON course_pricing(course_id);
CREATE INDEX IF NOT EXISTS idx_course_pricing_user_id ON course_pricing(user_id);
CREATE INDEX IF NOT EXISTS idx_payments_user_id ON payments(user_id);
CREATE INDEX IF NOT EXISTS idx_payments_course_id ON payments(course_id);
CREATE INDEX IF NOT EXISTS idx_payments_status ON payments(status);
CREATE INDEX IF NOT EXISTS idx_course_progress_user_id ON course_progress(user_id);
CREATE INDEX IF NOT EXISTS idx_course_progress_course_id ON course_progress(course_id);
CREATE INDEX IF NOT EXISTS idx_user_activity_user_id ON user_activity(user_id);
CREATE INDEX IF NOT EXISTS idx_user_activity_last_active ON user_activity(last_active);

-- Enable Row Level Security
ALTER TABLE course_pricing ENABLE ROW LEVEL SECURITY;
ALTER TABLE payments ENABLE ROW LEVEL SECURITY;
ALTER TABLE course_progress ENABLE ROW LEVEL SECURITY;
ALTER TABLE user_activity ENABLE ROW LEVEL SECURITY;

-- Create RLS policies for course_pricing
CREATE POLICY "Users can view their own course enrollments"
    ON course_pricing FOR SELECT
    USING (auth.uid() = user_id);

CREATE POLICY "Users can enroll in courses"
    ON course_pricing FOR INSERT
    WITH CHECK (auth.uid() = user_id);

-- Create RLS policies for payments
CREATE POLICY "Users can view their own payments"
    ON payments FOR SELECT
    USING (auth.uid() = user_id);

CREATE POLICY "Users can create their own payments"
    ON payments FOR INSERT
    WITH CHECK (auth.uid() = user_id);

-- Create RLS policies for course_progress
CREATE POLICY "Users can view their own progress"
    ON course_progress FOR SELECT
    USING (auth.uid() = user_id);

CREATE POLICY "Users can update their own progress"
    ON course_progress FOR UPDATE
    USING (auth.uid() = user_id)
    WITH CHECK (auth.uid() = user_id);

-- Create RLS policies for user_activity
CREATE POLICY "Users can view their own activity"
    ON user_activity FOR SELECT
    USING (auth.uid() = user_id);

CREATE POLICY "Users can update their own activity"
    ON user_activity FOR UPDATE
    USING (auth.uid() = user_id)
    WITH CHECK (auth.uid() = user_id);

-- Create function to update updated_at timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = TIMEZONE('utc'::text, NOW());
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create triggers for updated_at
CREATE TRIGGER update_course_pricing_updated_at
    BEFORE UPDATE ON course_pricing
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_payments_updated_at
    BEFORE UPDATE ON payments
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_course_progress_updated_at
    BEFORE UPDATE ON course_progress
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_user_activity_updated_at
    BEFORE UPDATE ON user_activity
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column(); 