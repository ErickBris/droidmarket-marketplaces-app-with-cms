package com.bk.lrandom.droidmarket.adapters;

import java.util.ArrayList;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.support.v4.view.PagerAdapter;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RelativeLayout;

import com.bk.lrandom.droidmarket.DetailActivity;
import com.bk.lrandom.droidmarket.ImagePreviewActivity;
import com.bk.lrandom.droidmarket.R;
import com.bk.lrandom.droidmarket.business.TouchImageView;
import com.bk.lrandom.droidmarket.confs.constants;
import com.koushikdutta.ion.Ion;

public class FullScreenImageAdapter extends PagerAdapter {
	private Activity activity;
	private ArrayList<String> paths;
	private LayoutInflater inflater;

	// constructor
	public FullScreenImageAdapter(Activity activity,
			ArrayList<String> imagePaths) {
		this.activity = activity;
		this.paths = imagePaths;
	}

	@Override
	public int getCount() {
		return this.paths.size();
	}

	@Override
	public boolean isViewFromObject(View view, Object object) {
		return view == ((RelativeLayout) object);
	}

	@Override
	public Object instantiateItem(ViewGroup container, final int position) {
		TouchImageView imgDisplay;
		inflater = (LayoutInflater) activity
				.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
		View viewLayout = inflater.inflate(R.layout.full_preview_item,
				container, false);

		imgDisplay = (TouchImageView) viewLayout.findViewById(R.id.imgDisplay);
		Ion.with(activity, paths.get(position)).withBitmap()
				.error(R.drawable.no_photo).placeholder(R.drawable.no_photo)
				.intoImageView(imgDisplay);
		(container).addView(viewLayout);
      //  imgDisplay.setT

        imgDisplay.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View arg0) {
                // TODO Auto-generated method stub
                Intent intent = new Intent(activity,
                        ImagePreviewActivity.class);
                intent.putStringArrayListExtra(
                        constants.IMAGES_PATH, paths);
                intent.putExtra(constants.IMAGE_POSITION, position);
                activity.startActivity(intent);
            }
        });

		return viewLayout;
	}

	@Override
	public void destroyItem(ViewGroup container, int position, Object object) {
		(container).removeView((RelativeLayout) object);
	}
}
